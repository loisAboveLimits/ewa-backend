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

/* olivero:teaser */
class __TwigTemplate_2de6f54c91c92d92117ed13f8f7cdf90 extends Template
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
            'prefix' => [$this, 'block_prefix'],
            'meta' => [$this, 'block_meta'],
            'image' => [$this, 'block_image'],
            'title' => [$this, 'block_title'],
            'content' => [$this, 'block_content'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 1
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\TwigExtension']->attachLibrary("core/components.olivero--teaser"));
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\ComponentsTwigExtension']->addAdditionalContext($context, "olivero:teaser"));
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\ComponentsTwigExtension']->validateProps($context, "olivero:teaser"));
        yield "<article";
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", ["teaser"], "method", false, false, true, 1), "html", null, true);
        yield ">
  <header>
    ";
        // line 3
        yield from $this->unwrap()->yieldBlock('prefix', $context, $blocks);
        // line 4
        yield "    <div class=\"teaser__meta\">
      ";
        // line 5
        yield from $this->unwrap()->yieldBlock('meta', $context, $blocks);
        // line 6
        yield "    </div>
    <div class=\"teaser__top\">
      <div class=\"teaser__image\">";
        // line 8
        yield from $this->unwrap()->yieldBlock('image', $context, $blocks);
        yield "</div>
      ";
        // line 9
        yield from $this->unwrap()->yieldBlock('title', $context, $blocks);
        // line 10
        yield "    </div>
  </header>
  <div class=\"teaser__content\">
    ";
        // line 13
        yield from $this->unwrap()->yieldBlock('content', $context, $blocks);
        // line 14
        yield "  </div>
</article>
";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["attributes"]);        return; yield;
    }

    // line 3
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_prefix(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        return; yield;
    }

    // line 5
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_meta(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        return; yield;
    }

    // line 8
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_image(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        return; yield;
    }

    // line 9
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_title(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        return; yield;
    }

    // line 13
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_content(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        return; yield;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "olivero:teaser";
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
        return array (  131 => 13,  121 => 9,  111 => 8,  101 => 5,  91 => 3,  83 => 14,  81 => 13,  76 => 10,  74 => 9,  70 => 8,  66 => 6,  64 => 5,  61 => 4,  59 => 3,  50 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "olivero:teaser", "C:\\xampp\\htdocs\\ewa/core/themes/olivero\\components\\teaser\\teaser.twig");
    }
    
    public function ensureSecurityChecked(): void
    {
        if ($this->sandbox->isSandboxed($this->source)) {
            $this->checkSecurity();
        }
    }
    
    public function checkSecurity()
    {
        static $tags = ["block" => 3];
        static $filters = ["escape" => 1];
        static $functions = [];
        static $tests = [];

        try {
            $this->sandbox->checkSecurity(
                [0 => "block"],
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
