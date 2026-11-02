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

/* @claro/entity-add-list.html.twig */
class __TwigTemplate_6a3a1a8ecdb4b0fd403045ce021955ac extends Template
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
        // line 19
        $context["item_classes"] = ["admin-item"];
        // line 23
        if ( !Twig\Extension\CoreExtension::testEmpty(($context["bundles"] ?? null))) {
            // line 24
            yield "  <dl";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", ["admin-list"], "method", false, false, true, 24), "html", null, true);
            yield ">
    ";
            // line 25
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(($context["bundles"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["bundle"]) {
                // line 26
                yield "      ";
                // line 30
                yield "      ";
                $context["bundle_attributes"] = (((($tmp = CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["bundle"], "add_link", [], "any", false, false, true, 30), "url", [], "any", false, false, true, 30), "getOption", ["attributes"], "method", false, false, true, 30)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["bundle"], "add_link", [], "any", false, false, true, 30), "url", [], "any", false, false, true, 30), "getOption", ["attributes"], "method", false, false, true, 30)) : ([]));
                // line 31
                yield "      ";
                $context["link_attributes"] = CoreExtension::getAttribute($this->env, $this->source, $this->extensions['Drupal\Core\Template\TwigExtension']->createAttribute(($context["bundle_attributes"] ?? null)), "addClass", ["admin-item__link"], "method", false, false, true, 31);
                // line 32
                yield "      <div";
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->createAttribute(["class" => ($context["item_classes"] ?? null)]), "html", null, true);
                yield ">
        <dt class=\"admin-item__title\">
          <a href=\"";
                // line 34
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["bundle"], "add_link", [], "any", false, false, true, 34), "url", [], "any", false, false, true, 34), "html", null, true);
                yield "\"";
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->withoutFilter(($context["link_attributes"] ?? null), "href"), "html", null, true);
                yield ">
            ";
                // line 35
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["bundle"], "add_link", [], "any", false, false, true, 35), "text", [], "any", false, false, true, 35), "html", null, true);
                yield "
          </a>
        </dt>
        ";
                // line 39
                yield "        ";
                if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["bundle"], "description", [], "any", false, false, true, 39)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                    // line 40
                    yield "          <dd class=\"admin-item__description\">";
                    yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, $context["bundle"], "description", [], "any", false, false, true, 40), "html", null, true);
                    yield "</dd>
        ";
                }
                // line 42
                yield "      </div>
    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['bundle'], $context['_parent']);
            $context = array_intersect_key($context, $_parent);
            $context += $_parent;
            // line 44
            yield "  </dl>
";
        } elseif ( !Twig\Extension\CoreExtension::testEmpty(        // line 45
($context["add_bundle_message"] ?? null))) {
            // line 46
            yield "  <p>
    ";
            // line 47
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["add_bundle_message"] ?? null), "html", null, true);
            yield "
  </p>
";
        }
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["bundles", "attributes", "add_bundle_message"]);        return; yield;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "@claro/entity-add-list.html.twig";
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
        return array (  109 => 47,  106 => 46,  104 => 45,  101 => 44,  93 => 42,  87 => 40,  84 => 39,  78 => 35,  72 => 34,  66 => 32,  63 => 31,  60 => 30,  58 => 26,  54 => 25,  49 => 24,  47 => 23,  45 => 19,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "@claro/entity-add-list.html.twig", "C:\\xampp\\htdocs\\ewa\\core\\themes\\claro\\templates\\entity-add-list.html.twig");
    }
    
    public function ensureSecurityChecked(): void
    {
        if ($this->sandbox->isSandboxed($this->source)) {
            $this->checkSecurity();
        }
    }
    
    public function checkSecurity()
    {
        static $tags = ["set" => 19, "if" => 23, "for" => 25];
        static $filters = ["escape" => 24, "without" => 34];
        static $functions = ["create_attribute" => 31];
        static $tests = [];

        try {
            $this->sandbox->checkSecurity(
                [0 => "set", 1 => "if", 2 => "for"],
                [0 => "escape", 1 => "without"],
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
