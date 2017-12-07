<?php

namespace ArchivesOnlineSGV;

//Autoload
spl_autoload_register(
    /**
     * Includes the class files at every new keyword
    * @param string $class
    * @throws \Exception
    */
    function ($class) {
    $namespace = __NAMESPACE__ . "\\";
    if (\strpos($class, $namespace) === 0) {
        include \str_replace(["_", $namespace], [DIRECTORY_SEPARATOR, ""], $class) . ".php";
    } else {
        throw new \Exception("Not included exception");
    }
});

/**
 * This is the entry function where the application starts
 */
function main() {
    new Controller_Api();
}

main();

?>

