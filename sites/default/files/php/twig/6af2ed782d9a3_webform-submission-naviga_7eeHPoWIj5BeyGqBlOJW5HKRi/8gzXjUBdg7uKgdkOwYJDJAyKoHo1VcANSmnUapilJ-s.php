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

/* modules/contrib/webform/templates/webform-submission-navigation.html.twig */
class __TwigTemplate_acba992e676ab559e8c63d09bfba2565 extends Template
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
        // line 17
        if (((($tmp = ($context["prev_url"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp) || (($tmp = ($context["next_url"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp))) {
            // line 18
            yield "  <nav id=\"webform-submission-navigation-";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["webform_id"] ?? null), "html", null, true);
            yield "\" class=\"webform-submission-navigation\" role=\"navigation\" aria-labelledby=\"webform-submission-label-";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["webform_id"] ?? null), "html", null, true);
            yield "\">
    <h2 class=\"visually-hidden\" id=\"webform-submission-label-";
            // line 19
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["webform_id"] ?? null), "html", null, true);
            yield "\">Submission navigation links for ";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["webform_title"] ?? null), "html", null, true);
            yield "</h2>
    <ul class=\"webform-submission-pager\">
      ";
            // line 21
            if ((($tmp = ($context["prev_url"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 22
                yield "        <li class=\"webform-submission-pager__item webform-submission-pager__item--previous\">
          <a href=\"";
                // line 23
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["prev_url"] ?? null), "html", null, true);
                yield "\" rel=\"prev\" title=\"";
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Go to previous page"));
                yield "\"><b>";
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("‹"));
                yield "</b> ";
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Previous submission"));
                yield "</a>
        </li>
      ";
            }
            // line 26
            yield "      ";
            if ((($tmp = ($context["next_url"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 27
                yield "        <li class=\"webform-submission-pager__item webform-submission-pager__item--next\">
          <a href=\"";
                // line 28
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["next_url"] ?? null), "html", null, true);
                yield "\" rel=\"next\" title=\"";
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Go to next page"));
                yield "\">";
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Next submission"));
                yield " <b>";
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("›"));
                yield "</b></a>
        </li>
      ";
            }
            // line 31
            yield "    </ul>
  </nav>
";
        }
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["prev_url", "next_url", "webform_id", "webform_title"]);        return; yield;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "modules/contrib/webform/templates/webform-submission-navigation.html.twig";
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
        return array (  96 => 31,  84 => 28,  81 => 27,  78 => 26,  66 => 23,  63 => 22,  61 => 21,  54 => 19,  47 => 18,  45 => 17,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "modules/contrib/webform/templates/webform-submission-navigation.html.twig", "C:\\xampp\\htdocs\\ewa\\modules\\contrib\\webform\\templates\\webform-submission-navigation.html.twig");
    }
    
    public function ensureSecurityChecked(): void
    {
        if ($this->sandbox->isSandboxed($this->source)) {
            $this->checkSecurity();
        }
    }
    
    public function checkSecurity()
    {
        static $tags = ["if" => 17];
        static $filters = ["escape" => 18, "t" => 23];
        static $functions = [];
        static $tests = [];

        try {
            $this->sandbox->checkSecurity(
                [0 => "if"],
                [0 => "escape", 1 => "t"],
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
