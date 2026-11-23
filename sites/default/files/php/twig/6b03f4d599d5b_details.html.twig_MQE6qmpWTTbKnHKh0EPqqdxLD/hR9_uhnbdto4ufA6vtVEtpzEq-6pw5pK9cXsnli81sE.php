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

/* core/themes/olivero/templates/form/details.html.twig */
class __TwigTemplate_b9f93a6dac032ee16525d994a5162a3c extends Template
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
        // line 20
        $context["classes"] = ["olivero-details"];
        // line 25
        $context["content_wrapper_classes"] = ["olivero-details__wrapper", "details-wrapper"];
        // line 30
        yield "<details";
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [($context["classes"] ?? null)], "method", false, false, true, 30), "html", null, true);
        yield ">";
        // line 31
        if ((($tmp = ($context["title"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 33
            $context["summary_classes"] = ["olivero-details__summary", (((($tmp =             // line 35
($context["required"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("js-form-required") : ("")), (((($tmp =             // line 36
($context["required"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("form-required") : (""))];
            // line 39
            yield "    <summary";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["summary_attributes"] ?? null), "addClass", [($context["summary_classes"] ?? null)], "method", false, false, true, 39), "html", null, true);
            yield ">";
            // line 40
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["title"] ?? null), "html", null, true);
            // line 41
            if ((($tmp = ($context["required"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 42
                yield "<span class=\"required-mark\"></span>";
            }
            // line 44
            yield "</summary>";
        }
        // line 46
        yield "<div";
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["content_attributes"] ?? null), "addClass", [($context["content_wrapper_classes"] ?? null)], "method", false, false, true, 46), "html", null, true);
        yield ">
    ";
        // line 47
        if ((($tmp = ($context["errors"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 48
            yield "      <div class=\"form-item form-item--error-message\">
        ";
            // line 49
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["errors"] ?? null), "html", null, true);
            yield "
      </div>
    ";
        }
        // line 52
        if ((($tmp = ($context["description"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 53
            $context["description_attributes"] = $this->extensions['Drupal\Core\Template\TwigExtension']->createAttribute(["id" => (($_v0 = ($context["attributes"] ?? null)) && is_array($_v0) || $_v0 instanceof ArrayAccess && in_array($_v0::class, CoreExtension::ARRAY_LIKE_CLASSES, true) ? ($_v0["aria-describedby"] ?? null) : CoreExtension::getAttribute($this->env, $this->source, ($context["attributes"] ?? null), "aria-describedby", [], "array", false, false, true, 53))]);
            // line 54
            yield "      <div";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["description_attributes"] ?? null), "addClass", [["olivero-details__description"]], "method", false, false, true, 54), "html", null, true);
            yield ">";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["description"] ?? null), "html", null, true);
            yield "</div>";
        }
        // line 56
        if ((($tmp = ($context["children"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 57
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["children"] ?? null), "html", null, true);
        }
        // line 59
        if ((($tmp = ($context["value"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 60
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["value"] ?? null), "html", null, true);
        }
        // line 62
        yield "</div>
</details>
";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["attributes", "title", "required", "summary_attributes", "content_attributes", "errors", "description", "children", "value"]);        return; yield;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "core/themes/olivero/templates/form/details.html.twig";
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
        return array (  110 => 62,  107 => 60,  105 => 59,  102 => 57,  100 => 56,  93 => 54,  91 => 53,  89 => 52,  83 => 49,  80 => 48,  78 => 47,  73 => 46,  70 => 44,  67 => 42,  65 => 41,  63 => 40,  59 => 39,  57 => 36,  56 => 35,  55 => 33,  53 => 31,  49 => 30,  47 => 25,  45 => 20,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "core/themes/olivero/templates/form/details.html.twig", "C:\\xampp\\htdocs\\ewa\\core\\themes\\olivero\\templates\\form\\details.html.twig");
    }
    
    public function ensureSecurityChecked(): void
    {
        if ($this->sandbox->isSandboxed($this->source)) {
            $this->checkSecurity();
        }
    }
    
    public function checkSecurity()
    {
        static $tags = ["set" => 20, "if" => 31];
        static $filters = ["escape" => 30];
        static $functions = ["create_attribute" => 53];
        static $tests = [];

        try {
            $this->sandbox->checkSecurity(
                [0 => "set", 1 => "if"],
                [0 => "escape"],
                [0 => "create_attribute"],
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
