<?php
// Micro library for a template system inspired form Slim 
// Renders templates declared in this directory

final class PhpTemplateNotFoundException extends \RuntimeException {};

function protected_include_scope(string $template, array $data) {
    extract($data);
    include func_get_arg(0);
}

function render_template($template, $data) {
    $template_path = __DIR__. "/" . $template;
    if (!is_file($template_path)) {
        throw new PhpTemplateNotFoundException('Cannot render "' . $template_path
                                               . '" because the template does not exist');
    }
    try {
        ob_start();
        protected_include_scope($template_path, $data);
        $output = ob_get_clean();
    } catch (Throwable $e) {
        ob_end_clean();
        throw $e;
    }

    return $output;
}

