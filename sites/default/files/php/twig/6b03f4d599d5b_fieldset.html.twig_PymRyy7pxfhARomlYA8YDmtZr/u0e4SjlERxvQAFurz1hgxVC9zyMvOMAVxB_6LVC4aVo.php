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

/* core/themes/olivero/templates/form/fieldset.html.twig */
class __TwigTemplate_228272832bff536c97ad4e43bccadc16 extends Template
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
        // line 33
        $context["classes"] = ["fieldset", (((($tmp = CoreExtension::getAttribute($this->env, $this->source,         // line 35
($context["attributes"] ?? null), "hasClass", ["fieldgroup"], "method", false, false, true, 35)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("fieldset--group") : ("")), "js-form-item", "form-item", "js-form-wrapper", "form-wrapper"];
        // line 43
        $context["wrapper_classes"] = ["fieldset__wrapper", (((($tmp = CoreExtension::getAttribute($this->env, $this->source,         // line 45
($context["attributes"] ?? null), "hasClass", ["fieldgroup"], "method", false, false, true, 45)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("fieldset__wrapper--group") : (""))];
        // line 49
        $context["legend_span_classes"] = ["fieldset__label", (((($tmp = CoreExtension::getAttribute($this->env, $this->source,         // line 51
($context["attributes"] ?? null), "hasClass", ["fieldgroup"], "method", false, false, true, 51)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("fieldset__label--group") : ("")), (((($tmp =         // line 52
($context["required"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("js-form-required") : ("")), (((($tmp =         // line 53
($context["required"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("form-required") : (""))];
        // line 57
        $context["legend_classes"] = ["fieldset__legend", ((((($tmp = CoreExtension::getAttribute($this->env, $this->source,         // line 59
($context["attributes"] ?? null), "hasClass", ["fieldgroup"], "method", false, false, true, 59)) && $tmp instanceof Markup ? (string) $tmp : $tmp) &&  !(($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["attributes"] ?? null), "hasClass", ["form-composite"], "method", false, false, true, 59)) && $tmp instanceof Markup ? (string) $tmp : $tmp))) ? ("fieldset__legend--group") : ("")), (((($tmp = CoreExtension::getAttribute($this->env, $this->source,         // line 60
($context["attributes"] ?? null), "hasClass", ["form-composite"], "method", false, false, true, 60)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("fieldset__legend--composite") : ("")), (((        // line 61
($context["title_display"] ?? null) == "invisible")) ? ("fieldset__legend--invisible") : ("fieldset__legend--visible"))];
        // line 65
        $context["description_classes"] = ["fieldset__description"];
        // line 69
        yield "
<fieldset";
        // line 70
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [($context["classes"] ?? null)], "method", false, false, true, 70), "html", null, true);
        yield ">
  ";
        // line 72
        yield "  ";
        if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["legend"] ?? null), "title", [], "any", false, false, true, 72)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 73
            yield "  <legend";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["legend"] ?? null), "attributes", [], "any", false, false, true, 73), "addClass", [($context["legend_classes"] ?? null)], "method", false, false, true, 73), "html", null, true);
            yield ">
    <span";
            // line 74
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["legend_span"] ?? null), "attributes", [], "any", false, false, true, 74), "addClass", [($context["legend_span_classes"] ?? null)], "method", false, false, true, 74), "html", null, true);
            yield ">";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["legend"] ?? null), "title", [], "any", false, false, true, 74), "html", null, true);
            yield "</span>
  </legend>
  ";
        }
        // line 77
        yield "
  <div";
        // line 78
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["content_attributes"] ?? null), "addClass", [($context["wrapper_classes"] ?? null)], "method", false, false, true, 78), "html", null, true);
        yield ">
    ";
        // line 79
        if ((($tmp = ($context["inline_items"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 80
            yield "      <div class=\"container-inline\">
    ";
        }
        // line 82
        yield "
    ";
        // line 83
        if (((($context["description_display"] ?? null) == "before") && (($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["description"] ?? null), "content", [], "any", false, false, true, 83)) && $tmp instanceof Markup ? (string) $tmp : $tmp))) {
            // line 84
            yield "      <div";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["description"] ?? null), "attributes", [], "any", false, false, true, 84), "addClass", [($context["description_classes"] ?? null)], "method", false, false, true, 84), "html", null, true);
            yield ">";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["description"] ?? null), "content", [], "any", false, false, true, 84), "html", null, true);
            yield "</div>
    ";
        }
        // line 86
        yield "    ";
        if ((($tmp = ($context["prefix"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 87
            yield "      <span class=\"fieldset__prefix\">";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["prefix"] ?? null), "html", null, true);
            yield "</span>
    ";
        }
        // line 89
        yield "    ";
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["children"] ?? null), "html", null, true);
        yield "
    ";
        // line 90
        if ((($tmp = ($context["suffix"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 91
            yield "      <span class=\"fieldset__suffix\">";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["suffix"] ?? null), "html", null, true);
            yield "</span>
    ";
        }
        // line 93
        yield "    ";
        if ((($tmp = ($context["errors"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 94
            yield "      <div class=\"fieldset__error-message\">
        ";
            // line 95
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["errors"] ?? null), "html", null, true);
            yield "
      </div>
    ";
        }
        // line 98
        yield "    ";
        if ((CoreExtension::inFilter(($context["description_display"] ?? null), ["after", "invisible"]) && (($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["description"] ?? null), "content", [], "any", false, false, true, 98)) && $tmp instanceof Markup ? (string) $tmp : $tmp))) {
            // line 99
            yield "      <div";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["description"] ?? null), "attributes", [], "any", false, false, true, 99), "addClass", [($context["description_classes"] ?? null)], "method", false, false, true, 99), "html", null, true);
            yield ">";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["description"] ?? null), "content", [], "any", false, false, true, 99), "html", null, true);
            yield "</div>
    ";
        }
        // line 101
        yield "
    ";
        // line 102
        if ((($tmp = ($context["inline_items"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 103
            yield "      </div>
    ";
        }
        // line 105
        yield "  </div>
</fieldset>
";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["attributes", "required", "title_display", "legend", "legend_span", "content_attributes", "inline_items", "description_display", "description", "prefix", "children", "suffix", "errors"]);        return; yield;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "core/themes/olivero/templates/form/fieldset.html.twig";
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
        return array (  166 => 105,  162 => 103,  160 => 102,  157 => 101,  149 => 99,  146 => 98,  140 => 95,  137 => 94,  134 => 93,  128 => 91,  126 => 90,  121 => 89,  115 => 87,  112 => 86,  104 => 84,  102 => 83,  99 => 82,  95 => 80,  93 => 79,  89 => 78,  86 => 77,  78 => 74,  73 => 73,  70 => 72,  66 => 70,  63 => 69,  61 => 65,  59 => 61,  58 => 60,  57 => 59,  56 => 57,  54 => 53,  53 => 52,  52 => 51,  51 => 49,  49 => 45,  48 => 43,  46 => 35,  45 => 33,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "core/themes/olivero/templates/form/fieldset.html.twig", "C:\\xampp\\htdocs\\ewa\\core\\themes\\olivero\\templates\\form\\fieldset.html.twig");
    }
    
    public function ensureSecurityChecked(): void
    {
        if ($this->sandbox->isSandboxed($this->source)) {
            $this->checkSecurity();
        }
    }
    
    public function checkSecurity()
    {
        static $tags = ["set" => 33, "if" => 72];
        static $filters = ["escape" => 70];
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
