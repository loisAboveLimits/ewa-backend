<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\MacroNamespace;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Sandbox\SecurityNotAllowedTestError;
use Twig\Source;
use Twig\Template;
use Twig\TemplateWrapper;

/* core/modules/ckeditor5/templates/ckeditor5-settings-toolbar.html.twig */
class __TwigTemplate_722d92b8d3d4787b2632b5dd1fdb3145 extends Template
{
    private Source $source;
    /**
     * @var array<string, MacroNamespace>
     */
    private array $macros = [];

    public function __construct(Environment $env)
    {
        $this->sandbox = $env->getExtension(SandboxExtension::class)->getChecker();
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 13
        yield "<fieldset aria-labelledby=\"ckeditor5-toolbar-configuration\" aria-describedby=\"ckeditor5-toolbar-description\">
  <legend id=\"ckeditor5-toolbar-configuration\">";
        // line 14
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Toolbar configuration"));
        yield "</legend>
  <div class=\"fieldset-wrapper\">
    <div id=\"ckeditor5-toolbar-description\" class=\"fieldset-description\">";
        // line 17
        yield t("Move a button into the <em>Active toolbar</em> to enable it, or into the list of <em>Available buttons</em> to disable it. Buttons may be moved with the mouse or keyboard arrow keys.", []);
        // line 20
        yield "</div>
    <div id=\"ckeditor5-toolbar-app\"></div>
    ";
        // line 22
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["form"] ?? null), "html", null, true);
        yield "
  </div>
</fieldset>
";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["form"]);        return; yield;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "core/modules/ckeditor5/templates/ckeditor5-settings-toolbar.html.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable(): bool
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefaultEscapeStrategy(): string|false
    {
        return "html";
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  59 => 22,  55 => 20,  53 => 17,  48 => 14,  45 => 13,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "core/modules/ckeditor5/templates/ckeditor5-settings-toolbar.html.twig", "C:\\xampp\\htdocs\\ewa\\core\\modules\\ckeditor5\\templates\\ckeditor5-settings-toolbar.html.twig");
    }
    
    public function ensureSecurityChecked(): void
    {
        if ($this->sandbox->isSandboxed($this->source)) {
            $this->checkSecurity();
        }
    }
    
    public function checkSecurity()
    {
        static $tags = ["trans" => 17];
        static $filters = ["t" => 14, "escape" => 22];
        static $functions = [];
        static $tests = [];

        try {
            $this->sandbox->checkSecurity(
                [0 => "trans"],
                [0 => "t", 1 => "escape"],
                [],
                [],
                $this->source
            );
        } catch (SecurityError $e) {
            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            } elseif ($e instanceof SecurityNotAllowedTestError && isset($tests[$e->getTestName()])) {
                $e->setTemplateLine($tests[$e->getTestName()]);
            }

            throw $e;
        }

    }
}
