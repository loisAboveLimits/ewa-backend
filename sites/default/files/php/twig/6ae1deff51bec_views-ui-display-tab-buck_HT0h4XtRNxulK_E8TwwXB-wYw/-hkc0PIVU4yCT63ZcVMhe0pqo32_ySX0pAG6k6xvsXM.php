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

/* core/themes/claro/templates/views/views-ui-display-tab-bucket.html.twig */
class __TwigTemplate_a7534233cd60221cdfd50bf861f98430 extends Template
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
        // line 21
        $context["classes"] = ["views-ui-display-tab-bucket", (((($tmp =         // line 23
($context["name"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? (\Drupal\Component\Utility\Html::getClass(($context["name"] ?? null))) : ("")), (((($tmp =         // line 24
($context["overridden"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("overridden") : (""))];
        // line 27
        yield "<div";
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [($context["classes"] ?? null)], "method", false, false, true, 27), "html", null, true);
        yield ">
  ";
        // line 28
        if (((($tmp = ($context["title"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp) || (($tmp = ($context["actions"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp))) {
            // line 29
            yield "    <div class=\"views-ui-display-tab-bucket__header";
            if ((($tmp = ($context["actions"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                yield " views-ui-display-tab-bucket__header--actions";
            }
            yield "\">
    ";
            // line 30
            if ((($tmp = ($context["title"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 31
                yield "<h3 class=\"views-ui-display-tab-bucket__title\">";
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["title"] ?? null), "html", null, true);
                yield "</h3>";
            }
            // line 33
            yield "    ";
            if ((($tmp = ($context["actions"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 34
                yield "<div class=\"views-ui-display-tab-bucket__actions\">";
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["actions"] ?? null), "html", null, true);
                yield "</div>";
            }
            // line 36
            yield "    </div>
  ";
        }
        // line 38
        yield "  ";
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["content"] ?? null), "html", null, true);
        yield "
</div>
";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["name", "overridden", "attributes", "title", "actions", "content"]);        return; yield;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "core/themes/claro/templates/views/views-ui-display-tab-bucket.html.twig";
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
        return array (  82 => 38,  78 => 36,  73 => 34,  70 => 33,  65 => 31,  63 => 30,  56 => 29,  54 => 28,  49 => 27,  47 => 24,  46 => 23,  45 => 21,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "core/themes/claro/templates/views/views-ui-display-tab-bucket.html.twig", "C:\\xampp\\htdocs\\ewa\\core\\themes\\claro\\templates\\views\\views-ui-display-tab-bucket.html.twig");
    }
    
    public function ensureSecurityChecked(): void
    {
        if ($this->sandbox->isSandboxed($this->source)) {
            $this->checkSecurity();
        }
    }
    
    public function checkSecurity()
    {
        static $tags = ["set" => 21, "if" => 28];
        static $filters = ["clean_class" => 23, "escape" => 27];
        static $functions = [];
        static $tests = [];

        try {
            $this->sandbox->checkSecurity(
                [0 => "set", 1 => "if"],
                [0 => "clean_class", 1 => "escape"],
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
