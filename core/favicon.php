<?php

// Dependencies:
if (!class_exists('JSON'))   require_once(__DIR__ . '/json.php');
if (!class_exists('Report')) require_once(__DIR__ . '/report.php');
if (!class_exists('Folder')) require_once(__DIR__ . '/folder.php');
if (!class_exists('File'))   require_once(__DIR__ . '/file.php');

/**
 * This class handles how the favicon is displayed,
 * and also how the favicon is generated.
 * 
 * @version     0.0.1
 * @package     Ombra
 * @subpackage  Favicon
 */
class Favicon {

    /**
     * The cached favicon object, this is updated when
     * the JSON::get method is called.
     */
    public static mixed $cache = null;

    /**
     * The path to the favicons.json file from the
     * root directory of the project.
     */
    private static string $file = "/favicons.json";

    /**
     * Retrieves favicons data from the specified location.
     *
     * @param string|null $location 
     * The location of the favicons.json file. Defaults to an empty string.
     * 
     * @return mixed 
     * The favicon data.
     */
    public static function get(?string $location = ""): mixed {
        $folder = sprintf("%s%s", Folder::getPath("favicon"), self::$file);
        return JSON::get($location, $folder, get_called_class());
    }

    /**
     * Sets the given data at the specified location in the json file.
     *
     * @param string $location 
     * The location where the data should be set.
     * 
     * @param mixed $data 
     * The data to be set.
     * 
     * @return bool 
     * Returns true if the data was successfully set, false otherwise.
     */
    public static function set(string $location, mixed $data): bool {
        return JSON::set($location, $data, self::$file, get_called_class());
    }

    /**
     * Deletes the data at the specified location in the json file.
     * 
     * @param string $location
     * The location where the data should be deleted.
     * 
     * @return bool
     * Returns true if the data was successfully deleted, false otherwise.
     */
    public static function remove(string $location): bool {
        return JSON::remove($location, self::$file, get_called_class());
    }

    /**
     * This method generates the favicon.
     * 
     * @return string|null
     * The generated favicon, or null if the favicon 
     * could not be generated.
     */
    public static function generate(): ?string {
        $favicons  = Favicon::get();
        $directory = Folder::getPath("favicon");

        if (empty($favicons) || !Folder::exists("favicon")) {
            Report::warning("The favicon JSON or directory is missing");
            return null;
        }

        $output = "";

        // Go through each favicon and generate the link tag:
        foreach ($favicons as $favicon) {
            $href = File::version($directory .'/'. $favicon->href);
            $rel  = $favicon->rel  ?? null;
            $type = $favicon->type ?? null;
            $size = $favicon->size ?? null;

            $link = sprintf('href="%s" rel="%s"', $href, $rel);
            if (isset($type)) $link .= sprintf(' type="%s"', $type);
            if (isset($size)) $link .= sprintf(' sizes="%s"', $size);

            $output .= sprintf('<link %s>', $link);
        }
        
        if (empty($output)) {
            Report::warning("No favicons generated");
            return null;
        }

        return $output;
    }

}