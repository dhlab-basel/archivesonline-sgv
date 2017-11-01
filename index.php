<?php

declare(strict_types = 1);

namespace ArchivesOnlineSGV;

// Config

// Autoload
spl_autoload_register(function (string $class) {
    $namespace = __NAMESPACE__ . "\\";
    if (\strpos($class, $namespace) === 0) {
        include \str_replace(["_", $namespace], [DIRECTORY_SEPARATOR, ""], $class) . ".php";
    } else {
        throw new \Exception("Not included exception");
    }
});

// Main
main();
function main() {
    new Controller_Api();
}

?>