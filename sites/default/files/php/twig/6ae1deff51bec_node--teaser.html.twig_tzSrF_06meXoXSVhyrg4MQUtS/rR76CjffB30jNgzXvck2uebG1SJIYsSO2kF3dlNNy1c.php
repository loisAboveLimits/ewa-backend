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

/* core/themes/olivero/templates/content/node--teaser.html.twig */
class __TwigTemplate_8e6a40c379070468c0045158c15116ed extends Template
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
        // line 63
        $context["classes"] = ["node", ("node--type-" . \Drupal\Component\Utility\Html::getClass(CoreExtension::getAttribute($this->env, $this->source,         // line 65
($context["node"] ?? null), "bundle", [], "any", false, false, true, 65))), (((($tmp = CoreExtension::getAttribute($this->env, $this->source,         // line 66
($context["node"] ?? null), "isPromoted", [], "method", false, false, true, 66)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("node--promoted") : ("")), (((($tmp = CoreExtension::getAttribute($this->env, $this->source,         // line 67
($context["node"] ?? null), "isSticky", [], "method", false, false, true, 67)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("node--sticky") : ("")), (( !(($tmp = CoreExtension::getAttribute($this->env, $this->source,         // line 68
($context["node"] ?? null), "isPublished", [], "method", false, false, true, 68)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("node--unpublished") : ("")), (((($tmp =         // line 69
($context["view_mode"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? (("node--view-mode-" . \Drupal\Component\Utility\Html::getClass(($context["view_mode"] ?? null)))) : (""))];
        // line 72
        yield "

";
        // line 74
        yield from $this->load("core/themes/olivero/templates/content/node--teaser.html.twig", 74, 1)->unwrap()->yield(CoreExtension::toArray(["attributes" => CoreExtension::getAttribute($this->env, $this->source,         // line 75
($context["attributes"] ?? null), "addClass", [($context["classes"] ?? null)], "method", false, false, true, 75), "author_attributes" =>         // line 76
($context["author_attributes"] ?? null), "author_name" =>         // line 77
($context["author_name"] ?? null), "content" =>         // line 78
($context["content"] ?? null), "date" =>         // line 79
($context["date"] ?? null), "display_submitted" =>         // line 80
($context["display_submitted"] ?? null), "label" =>         // line 81
($context["label"] ?? null), "metadata" =>         // line 82
($context["metadata"] ?? null), "title_attributes" =>         // line 83
($context["title_attributes"] ?? null), "title_prefix" =>         // line 84
($context["title_prefix"] ?? null), "title_suffix" =>         // line 85
($context["title_suffix"] ?? null), "read_more" =>         // line 86
($context["read_more"] ?? null), "url" =>         // line 87
($context["url"] ?? null)]));
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["node", "view_mode", "attributes", "author_attributes", "author_name", "content", "date", "display_submitted", "label", "metadata", "title_attributes", "title_prefix", "title_suffix", "read_more", "url"]);        return; yield;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "core/themes/olivero/templates/content/node--teaser.html.twig";
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
        return array (  72 => 87,  71 => 86,  70 => 85,  69 => 84,  68 => 83,  67 => 82,  66 => 81,  65 => 80,  64 => 79,  63 => 78,  62 => 77,  61 => 76,  60 => 75,  59 => 74,  55 => 72,  53 => 69,  52 => 68,  51 => 67,  50 => 66,  49 => 65,  48 => 63,  45 => 61,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "core/themes/olivero/templates/content/node--teaser.html.twig", "C:\\xampp\\htdocs\\ewa\\core\\themes\\olivero\\templates\\content\\node--teaser.html.twig");
    }
    
    public function ensureSecurityChecked(): void
    {
        if ($this->sandbox->isSandboxed($this->source)) {
            $this->checkSecurity();
        }
    }
    
    public function checkSecurity()
    {
        static $tags = ["set" => 63, "embed" => 74];
        static $filters = ["clean_class" => 65];
        static $functions = [];
        static $tests = [];

        try {
            $this->sandbox->checkSecurity(
                [0 => "set", 1 => "embed"],
                [0 => "clean_class"],
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


/* core/themes/olivero/templates/content/node--teaser.html.twig */
class __TwigTemplate_8e6a40c379070468c0045158c15116ed___1 extends Template
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

        $this->blocks = [
            'prefix' => [$this, 'block_prefix'],
            'meta' => [$this, 'block_meta'],
            'image' => [$this, 'block_image'],
            'title' => [$this, 'block_title'],
            'content' => [$this, 'block_content'],
        ];
    }

    protected function doGetParent(array $context): bool|string|Template|TemplateWrapper
    {
        // line 74
        return $this->parent ??= $this->load("olivero:teaser", 74);
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $this->parent = $this->load("olivero:teaser", 74);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["title_prefix", "title_suffix", "display_submitted", "author_attributes", "author_name", "date", "metadata", "content", "label", "title_attributes", "url"]);    }

    // line 89
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_prefix(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 90
        yield "    ";
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["title_prefix"] ?? null), "html", null, true);
        yield "
    ";
        // line 91
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["title_suffix"] ?? null), "html", null, true);
        yield "
  ";
        return; yield;
    }

    // line 93
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_meta(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 94
        yield "    ";
        if ((($tmp = ($context["display_submitted"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 95
            yield "      <div class=\"node__meta\">
        <span";
            // line 96
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["author_attributes"] ?? null), "html", null, true);
            yield ">
          ";
            // line 97
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("By"));
            yield " <span class=\"node__author\">";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["author_name"] ?? null), "html", null, true);
            yield "</span>, ";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["date"] ?? null), "html", null, true);
            yield "
        </span>
        ";
            // line 99
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["metadata"] ?? null), "html", null, true);
            yield "
      </div>
    ";
        }
        // line 102
        yield "  ";
        return; yield;
    }

    // line 103
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_image(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["content"] ?? null), "field_image", [], "any", false, false, true, 103), "html", null, true);
        return; yield;
    }

    // line 104
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_title(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 105
        yield "    ";
        if ((($tmp = ($context["label"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 106
            yield "      <h2";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["title_attributes"] ?? null), "addClass", ["node__title", "teaser__title"], "method", false, false, true, 106), "html", null, true);
            yield ">
        <a href=\"";
            // line 107
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["url"] ?? null), "html", null, true);
            yield "\" rel=\"bookmark\">";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["label"] ?? null), "html", null, true);
            yield "</a>
      </h2>
    ";
        }
        // line 110
        yield "  ";
        return; yield;
    }

    // line 111
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_content(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 112
        yield "    ";
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->withoutFilter(($context["content"] ?? null), "field_image", "links"), "html", null, true);
        yield "
  ";
        return; yield;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "core/themes/olivero/templates/content/node--teaser.html.twig";
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
        return array (  296 => 112,  289 => 111,  284 => 110,  276 => 107,  271 => 106,  268 => 105,  261 => 104,  250 => 103,  245 => 102,  239 => 99,  230 => 97,  226 => 96,  223 => 95,  220 => 94,  213 => 93,  206 => 91,  201 => 90,  194 => 89,  182 => 74,  72 => 87,  71 => 86,  70 => 85,  69 => 84,  68 => 83,  67 => 82,  66 => 81,  65 => 80,  64 => 79,  63 => 78,  62 => 77,  61 => 76,  60 => 75,  59 => 74,  55 => 72,  53 => 69,  52 => 68,  51 => 67,  50 => 66,  49 => 65,  48 => 63,  45 => 61,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "core/themes/olivero/templates/content/node--teaser.html.twig", "C:\\xampp\\htdocs\\ewa\\core\\themes\\olivero\\templates\\content\\node--teaser.html.twig");
    }
    
    public function ensureSecurityChecked(): void
    {
        if ($this->sandbox->isSandboxed($this->source)) {
            $this->checkSecurity();
        }
    }
    
    public function checkSecurity()
    {
        static $tags = ["extends" => 74, "if" => 94];
        static $filters = ["escape" => 90, "t" => 97, "without" => 112];
        static $functions = [];
        static $tests = [];

        try {
            $this->sandbox->checkSecurity(
                [0 => "extends", 1 => "if"],
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
