<?php

namespace CodeForceInfiniteScroll\App\Templates;

class Renderer 
{
    /**
     * @param string $template_name Template alias without path or extension
     * @param array $params 
     * 
     * @return null
     */
    public function render($template_name, Array $params = array()) 
    {
        $content = null;
        $template_path = $this->get_template_path_by($template_name);

        if ($template_path) {
            $content = $this->load_template_content($template_path, $params);
        }

        return $content;
    }

    /**
     * @param string $template_name Template alias without path or extension
     * 
     * @return string|null
     */
    public function get_template_path_by($template_name) 
    {
        if ($template_name) {
            $templates_directory = \CodeForceInfiniteScroll\PLUGIN_PATH . '/view';
            $template_path = $templates_directory . '/' . $template_name . '.php';

            if (file_exists($template_path)) {
                return $template_path;
            }
        }

        return null;
    }

    /**
     * @param string $template_path Full path to template
     * @param array $params 
     * 
     * @return string|null
     */
    private function load_template_content($template_path, Array $params = array()) 
    {
        if (!$template_path) {
            return null;
        }

        ob_start();
        $this->set_query_vars_for_template($params);
        load_template($template_path, false);
        $content = ob_get_clean();

        return $content;
    }

    private function set_query_vars_for_template(Array $params) 
    {
        foreach ($params as $title => $value) {
            set_query_var($title, $value);
        }
    }
}
