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

/* core/themes/olivero/templates/navigation/menu-local-tasks.html.twig */
class __TwigTemplate_c73a896637cc033bb389e3a02f6c8ec2 extends Template
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
        // line 16
        yield "
";
        // line 17
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->attachLibrary("olivero/tabs"), "html", null, true);
        yield "

";
        // line 19
        if ((($tmp = ($context["primary"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 20
            yield "  <h2 id=\"primary-tabs-title\" class=\"visually-hidden\">";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Primary tabs"));
            yield "</h2>
  <nav class=\"tabs-wrapper\" aria-labelledby=\"primary-tabs-title\" data-drupal-nav-primary-tabs>
    <ul class=\"tabs tabs--primary\">";
            // line 22
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["primary"] ?? null), "html", null, true);
            yield "</ul>
  </nav>
";
        }
        // line 25
        if ((($tmp = ($context["secondary"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 26
            yield "  <h2 id=\"secondary-tabs-title\" class=\"visually-hidden\">";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Secondary tabs"));
            yield "</h2>
  <nav class=\"tabs-wrapper\" aria-labelledby=\"secondary-tabs-title\">
    <ul class=\"tabs tabs--secondary\">";
            // line 28
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["secondary"] ?? null), "html", null, true);
            yield "</ul>
  </nav>
";
        }
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["primary", "secondary"]);        return; yield;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "core/themes/olivero/templates/navigation/menu-local-tasks.html.twig";
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
        return array (  75 => 28,  69 => 26,  67 => 25,  61 => 22,  55 => 20,  53 => 19,  48 => 17,  45 => 16,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "core/themes/olivero/templates/navigation/menu-local-tasks.html.twig", "C:\\xampp\\htdocs\\ewa\\core\\themes\\olivero\\templates\\navigation\\menu-local-tasks.html.twig");
    }
    
    public function ensureSecurityChecked(): void
    {
        if ($this->sandbox->isSandboxed($this->source)) {
            $this->checkSecurity();
        }
    }
    
    public function checkSecurity()
    {
        static $tags = ["if" => 19];
        static $filters = ["escape" => 17, "t" => 20];
        static $functions = ["attach_library" => 17];
        static $tests = [];

        try {
            $this->sandbox->checkSecurity(
                [0 => "if"],
                [0 => "escape", 1 => "t"],
                [0 => "attach_library"],
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
