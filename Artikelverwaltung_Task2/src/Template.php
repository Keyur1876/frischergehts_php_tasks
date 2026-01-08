<?php
declare(strict_types=1);

class Template
{
    private string $template;
    private array $vars = [];

    public function __construct(string $templateFile)
    {
        if (!is_file($templateFile)) {
            throw new RuntimeException("Template not found: {$templateFile}");
        }
        $this->template = file_get_contents($templateFile);
    }

    public function parse(array $vars): self
    {
        $this->vars = array_merge($this->vars, $vars);
        return $this;
    }

    public function render(): string
    {
        $out = $this->template;

        foreach ($this->vars as $key => $value) {
            // Escape by default to prevent XSS
            $safe = htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $out = str_replace('{{' . $key . '}}', $safe, $out);
        }

        // Remove unused placeholders (optional)
        $out = preg_replace('/{{\s*[a-zA-Z0-9_]+\s*}}/', '', $out);

        return $out;
    }
}
