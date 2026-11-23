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

/* core/themes/olivero/templates/datetime-wrapper.html.twig */
class __TwigTemplate_28e6d545b168312c0a8832fb06636132 extends Template
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
        // line 10
        $context["title_classes"] = ["form-item__label", (((($tmp =         // line 12
($context["required"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("js-form-required") : ("")), (((($tmp =         // line 13
($context["required"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("form-required") : (""))];
        // line 16
        yield "<div class=\"form-item form-datetime-wrapper\">
  ";
        // line 17
        if ((($tmp = ($context["title"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 18
            yield "    <h4";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["title_attributes"] ?? null), "addClass", [($context["title_classes"] ?? null)], "method", false, false, true, 18), "html", null, true);
            yield ">";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["title"] ?? null), "html", null, true);
            yield "</h4>
  ";
        }
        // line 20
        yield "  ";
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["content"] ?? null), "html", null, true);
        yield "
  ";
        // line 21
        if ((($tmp = ($context["errors"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 22
            yield "    <div class=\"form-item__error-message\">
      ";
            // line 23
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["errors"] ?? null), "html", null, true);
            yield "
    </div>
  ";
        }
        // line 26
        yield "  ";
        if ((($tmp = ($context["description"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 27
            yield "    <div";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["description_attributes"] ?? null), "addClass", ["form-item__description"], "method", false, false, true, 27), "html", null, true);
            yield ">
      ";
            // line 28
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["description"] ?? null), "html", null, true);
            yield "
    </div>
  ";
        }
        // line 31
        yield "</div>
";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["required", "title", "title_attributes", "content", "errors", "description", "description_attributes"]);        return; yield;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "core/themes/olivero/templates/datetime-wrapper.html.twig";
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
        return array (  92 => 31,  86 => 28,  81 => 27,  78 => 26,  72 => 23,  69 => 22,  67 => 21,  62 => 20,  54 => 18,  52 => 17,  49 => 16,  47 => 13,  46 => 12,  45 => 10,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "core/themes/olivero/templates/datetime-wrapper.html.twig", "C:\\xampp\\htdocs\\ewa\\core\\themes\\olivero\\templates\\datetime-wrapper.html.twig");
    }
    
    public function ensureSecurityChecked(): void
    {
        if ($this->sandbox->isSandboxed($this->source)) {
            $this->checkSecurity();
        }
    }
    
    public function checkSecurity()
    {
        static $tags = ["set" => 10, "if" => 17];
        static $filters = ["escape" => 18];
        static $functions = [];
        static $tests = [];

        try {
            $this->sandbox->checkSecurity(
                [0 => "set", 1 => "if"],
                [0 => "escape"],
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
