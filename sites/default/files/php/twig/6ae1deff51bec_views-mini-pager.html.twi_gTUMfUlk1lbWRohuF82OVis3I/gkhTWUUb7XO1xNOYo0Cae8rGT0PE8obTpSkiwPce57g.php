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

/* core/themes/olivero/templates/views/views-mini-pager.html.twig */
class __TwigTemplate_14e25004f0715ef65a9590d3d5a1eddd extends Template
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
        // line 14
        if (((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["items"] ?? null), "previous", [], "any", false, false, true, 14)) && $tmp instanceof Markup ? (string) $tmp : $tmp) || (($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["items"] ?? null), "next", [], "any", false, false, true, 14)) && $tmp instanceof Markup ? (string) $tmp : $tmp))) {
            // line 15
            yield "  <nav class=\"pager\" aria-labelledby=\"";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["heading_id"] ?? null), "html", null, true);
            yield "\">
    <";
            // line 16
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["pagination_heading_level"] ?? null), "html", null, true);
            yield " id=\"";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["heading_id"] ?? null), "html", null, true);
            yield "\" class=\"visually-hidden\">";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Pagination"));
            yield "</";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["pagination_heading_level"] ?? null), "html", null, true);
            yield ">
    <ul class=\"pager__items js-pager__items\">
      ";
            // line 19
            yield "      ";
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["items"] ?? null), "previous", [], "any", false, false, true, 19)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 20
                yield "        <li class=\"pager__item pager__item--control pager__item--previous\">
          <a href=\"";
                // line 21
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["items"] ?? null), "previous", [], "any", false, false, true, 21), "href", [], "any", false, false, true, 21), "html", null, true);
                yield "\" class=\"pager__link\" title=\"";
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Go to previous page"));
                yield "\" rel=\"prev\"";
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->withoutFilter(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["items"] ?? null), "previous", [], "any", false, false, true, 21), "attributes", [], "any", false, false, true, 21), "href", "title", "rel", "class"), "html", null, true);
                yield ">
            <span class=\"visually-hidden\">";
                // line 22
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Previous page"));
                yield "</span>";
                // line 23
                yield from $this->load("@olivero/../images/pager-previous.svg", 23)->unwrap()->yield($context);
                // line 24
                yield "</a>
        </li>
      ";
            }
            // line 27
            yield "
      ";
            // line 29
            yield "      ";
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["items"] ?? null), "current", [], "any", false, false, true, 29)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 30
                yield "        <li class=\"pager__item pager__item--active\">
          ";
                // line 31
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["items"] ?? null), "current", [], "any", false, false, true, 31), "html", null, true);
                yield "
        </li>
      ";
            }
            // line 34
            yield "
      ";
            // line 36
            yield "      ";
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["items"] ?? null), "next", [], "any", false, false, true, 36)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 37
                yield "        <li class=\"pager__item pager__item--control pager__item--next\">
          <a href=\"";
                // line 38
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["items"] ?? null), "next", [], "any", false, false, true, 38), "href", [], "any", false, false, true, 38), "html", null, true);
                yield "\" class=\"pager__link\" title=\"";
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Go to next page"));
                yield "\" rel=\"next\"";
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->withoutFilter(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["items"] ?? null), "next", [], "any", false, false, true, 38), "attributes", [], "any", false, false, true, 38), "href", "title", "rel"), "html", null, true);
                yield ">
            <span class=\"visually-hidden\">";
                // line 39
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Next page"));
                yield "</span>";
                // line 40
                yield from $this->load("@olivero/../images/pager-previous.svg", 40)->unwrap()->yield($context);
                // line 41
                yield "</a>
        </li>
      ";
            }
            // line 44
            yield "    </ul>
  </nav>
";
        }
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["items", "heading_id", "pagination_heading_level"]);        return; yield;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "core/themes/olivero/templates/views/views-mini-pager.html.twig";
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
        return array (  129 => 44,  124 => 41,  122 => 40,  119 => 39,  111 => 38,  108 => 37,  105 => 36,  102 => 34,  96 => 31,  93 => 30,  90 => 29,  87 => 27,  82 => 24,  80 => 23,  77 => 22,  69 => 21,  66 => 20,  63 => 19,  52 => 16,  47 => 15,  45 => 14,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "core/themes/olivero/templates/views/views-mini-pager.html.twig", "C:\\xampp\\htdocs\\ewa\\core\\themes\\olivero\\templates\\views\\views-mini-pager.html.twig");
    }
    
    public function ensureSecurityChecked(): void
    {
        if ($this->sandbox->isSandboxed($this->source)) {
            $this->checkSecurity();
        }
    }
    
    public function checkSecurity()
    {
        static $tags = ["if" => 14, "include" => 23];
        static $filters = ["escape" => 15, "t" => 16, "without" => 21];
        static $functions = [];
        static $tests = [];

        try {
            $this->sandbox->checkSecurity(
                [0 => "if", 1 => "include"],
                [0 => "escape", 1 => "t", 2 => "without"],
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
