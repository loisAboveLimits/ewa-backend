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

/* core/themes/olivero/templates/content/node.html.twig */
class __TwigTemplate_b5be6cc85d20d04f23c086e52db4e190 extends Template
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
        // line 61
        yield "
";
        // line 62
        $context["layout"] = (((($tmp = ($context["layout"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? (("layout--" . \Drupal\Component\Utility\Html::getClass(($context["layout"] ?? null)))) : (""));
        // line 63
        yield "
";
        // line 65
        $context["classes"] = ["node", ("node--type-" . \Drupal\Component\Utility\Html::getClass(CoreExtension::getAttribute($this->env, $this->source,         // line 67
($context["node"] ?? null), "bundle", [], "any", false, false, true, 67))), (((($tmp =         // line 68
($context["layout"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("grid-full") : ("")), (((($tmp = CoreExtension::getAttribute($this->env, $this->source,         // line 69
($context["node"] ?? null), "isPromoted", [], "method", false, false, true, 69)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("node--promoted") : ("")), (((($tmp = CoreExtension::getAttribute($this->env, $this->source,         // line 70
($context["node"] ?? null), "isSticky", [], "method", false, false, true, 70)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("node--sticky") : ("")), (( !(($tmp = CoreExtension::getAttribute($this->env, $this->source,         // line 71
($context["node"] ?? null), "isPublished", [], "method", false, false, true, 71)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("node--unpublished") : ("")), (((($tmp =         // line 72
($context["view_mode"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? (("node--view-mode-" . \Drupal\Component\Utility\Html::getClass(($context["view_mode"] ?? null)))) : (""))];
        // line 75
        yield "<article";
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [($context["classes"] ?? null)], "method", false, false, true, 75), "html", null, true);
        yield ">
  <header class=\"";
        // line 76
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["layout"] ?? null), "html", null, true);
        yield "\">
    ";
        // line 77
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["title_prefix"] ?? null), "html", null, true);
        yield "
      ";
        // line 78
        if (((($tmp = ($context["label"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp) && (($context["view_mode"] ?? null) != "full"))) {
            // line 79
            yield "      <h2";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["title_attributes"] ?? null), "addClass", ["node__title"], "method", false, false, true, 79), "html", null, true);
            yield ">
        <a href=\"";
            // line 80
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["url"] ?? null), "html", null, true);
            yield "\" rel=\"bookmark\">";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["label"] ?? null), "html", null, true);
            yield "</a>
      </h2>
    ";
        }
        // line 83
        yield "    ";
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["title_suffix"] ?? null), "html", null, true);
        yield "
    ";
        // line 84
        if ((($tmp = ($context["display_submitted"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 85
            yield "      <div class=\"node__meta\">
      ";
            // line 86
            if ((($tmp = ($context["author_picture"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 87
                yield "        <div class=\"node__author-image\">
          ";
                // line 88
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["author_picture"] ?? null), "html", null, true);
                yield "
        </div>
      ";
            }
            // line 91
            yield "        <span";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["author_attributes"] ?? null), "html", null, true);
            yield ">
          ";
            // line 92
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("By"));
            yield " <span class=\"node__author\">";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["author_name"] ?? null), "html", null, true);
            yield "</span>, ";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["date"] ?? null), "html", null, true);
            yield "
        </span>
        ";
            // line 94
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["metadata"] ?? null), "html", null, true);
            yield "
      </div>
    ";
        }
        // line 97
        yield "  </header>
  <div";
        // line 98
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["content_attributes"] ?? null), "addClass", ["node__content", ($context["layout"] ?? null)], "method", false, false, true, 98), "html", null, true);
        yield ">
    ";
        // line 100
        yield "    ";
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->withoutFilter(($context["content"] ?? null), "comment"), "html", null, true);
        yield "
  </div>
  ";
        // line 102
        if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["content"] ?? null), "comment", [], "any", false, false, true, 102)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 103
            yield "    <div id=\"comments\" class=\"";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["layout"] ?? null), "html", null, true);
            yield "\">
      ";
            // line 104
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["content"] ?? null), "comment", [], "any", false, false, true, 104), "html", null, true);
            yield "
    </div>
  ";
        }
        // line 107
        yield "</article>
";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["node", "view_mode", "attributes", "title_prefix", "label", "title_attributes", "url", "title_suffix", "display_submitted", "author_picture", "author_attributes", "author_name", "date", "metadata", "content_attributes", "content"]);        return; yield;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "core/themes/olivero/templates/content/node.html.twig";
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
        return array (  156 => 107,  150 => 104,  145 => 103,  143 => 102,  137 => 100,  133 => 98,  130 => 97,  124 => 94,  115 => 92,  110 => 91,  104 => 88,  101 => 87,  99 => 86,  96 => 85,  94 => 84,  89 => 83,  81 => 80,  76 => 79,  74 => 78,  70 => 77,  66 => 76,  61 => 75,  59 => 72,  58 => 71,  57 => 70,  56 => 69,  55 => 68,  54 => 67,  53 => 65,  50 => 63,  48 => 62,  45 => 61,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "core/themes/olivero/templates/content/node.html.twig", "C:\\xampp\\htdocs\\ewa\\core\\themes\\olivero\\templates\\content\\node.html.twig");
    }
    
    public function ensureSecurityChecked(): void
    {
        if ($this->sandbox->isSandboxed($this->source)) {
            $this->checkSecurity();
        }
    }
    
    public function checkSecurity()
    {
        static $tags = ["set" => 62, "if" => 78];
        static $filters = ["clean_class" => 62, "escape" => 75, "t" => 92, "without" => 100];
        static $functions = [];
        static $tests = [];

        try {
            $this->sandbox->checkSecurity(
                [0 => "set", 1 => "if"],
                [0 => "clean_class", 1 => "escape", 2 => "t", 3 => "without"],
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
