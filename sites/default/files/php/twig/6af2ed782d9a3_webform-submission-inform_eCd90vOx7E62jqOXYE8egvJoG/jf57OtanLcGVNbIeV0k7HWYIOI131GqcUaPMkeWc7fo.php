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

/* modules/contrib/webform/templates/webform-submission-information.html.twig */
class __TwigTemplate_57560cfbb20750a1b8d7bd11dddfb2a1 extends Template
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
        if ((($tmp = ($context["submissions_view"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 34
            yield "  <div><b>";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Submission Number"));
            yield ":</b> ";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["serial"] ?? null), "html", null, true);
            yield "</div>
  <div><b>";
            // line 35
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Submission ID"));
            yield ":</b> ";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["sid"] ?? null), "html", null, true);
            yield "</div>
  <div><b>";
            // line 36
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Submission UUID"));
            yield ":</b> ";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["uuid"] ?? null), "html", null, true);
            yield "</div>
  ";
            // line 37
            if ((($tmp = ($context["uri"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 38
                yield "    <div><b>";
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Submission URI"));
                yield ":</b> ";
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["uri"] ?? null), "html", null, true);
                yield "</div>
  ";
            }
            // line 40
            yield "  ";
            if ((($tmp = ($context["token_view"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 41
                yield "    <div><b>";
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Submission View"));
                yield ":</b> ";
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["token_view"] ?? null), "html", null, true);
                yield "</div>
  ";
            }
            // line 43
            yield "  ";
            if ((($tmp = ($context["token_update"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 44
                yield "    <div><b>";
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Submission Update"));
                yield ":</b> ";
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["token_update"] ?? null), "html", null, true);
                yield "</div>
  ";
            }
            // line 46
            yield "  ";
            if ((($tmp = ($context["token_delete"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 47
                yield "    <div><b>";
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Submission Delete"));
                yield ":</b> ";
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["token_delete"] ?? null), "html", null, true);
                yield "</div>
  ";
            }
            // line 49
            yield "  <br />
  <div><b>";
            // line 50
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Created"));
            yield ":</b> ";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["created"] ?? null), "html", null, true);
            yield "</div>
  <div><b>";
            // line 51
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Completed"));
            yield ":</b> ";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["completed"] ?? null), "html", null, true);
            yield "</div>
  <div><b>";
            // line 52
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Changed"));
            yield ":</b> ";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["changed"] ?? null), "html", null, true);
            yield "</div>
  <br />
  <div><b>";
            // line 54
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Remote IP address"));
            yield ":</b> ";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["remote_addr"] ?? null), "html", null, true);
            yield "</div>
  <div><b>";
            // line 55
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Submitted by"));
            yield ":</b> ";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["submitted_by"] ?? null), "html", null, true);
            yield "</div>
  <div><b>";
            // line 56
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Language"));
            yield ":</b> ";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["language"] ?? null), "html", null, true);
            yield "</div>
  <br />
  <div><b>";
            // line 58
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Is draft"));
            yield ":</b> ";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["is_draft"] ?? null), "html", null, true);
            yield "</div>
  ";
            // line 59
            if ((($tmp = ($context["current_page"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 60
                yield "    <div><b>";
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Current page"));
                yield ":</b> ";
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["current_page"] ?? null), "html", null, true);
                yield "</div>
  ";
            }
            // line 62
            yield "  <div><b>";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Webform"));
            yield ":</b> ";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["webform"] ?? null), "html", null, true);
            yield "</div>
  ";
            // line 63
            if ((($tmp = ($context["submitted_to"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 64
                yield "    <div><b>";
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Submitted to"));
                yield ":</b> ";
                yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["submitted_to"] ?? null), "html", null, true);
                yield "</div>
  ";
            }
            // line 66
            yield "  ";
            if ((((($tmp = ($context["sticky"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp) || (($tmp = ($context["locked"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) || (($tmp = ($context["notes"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp))) {
                // line 67
                yield "    <br />
    ";
                // line 68
                if ((($tmp = ($context["sticky"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                    // line 69
                    yield "      <div><b>";
                    yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Flagged"));
                    yield ":</b> ";
                    yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["sticky"] ?? null), "html", null, true);
                    yield "</div>
    ";
                }
                // line 71
                yield "    ";
                if ((($tmp = ($context["locked"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                    // line 72
                    yield "      <div><b>";
                    yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Locked"));
                    yield ":</b> ";
                    yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["locked"] ?? null), "html", null, true);
                    yield "</div>
    ";
                }
                // line 74
                yield "    ";
                if ((($tmp = ($context["notes"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                    // line 75
                    yield "      <div><b>";
                    yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Notes"));
                    yield ":</b><br/>
      <pre>";
                    // line 76
                    yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["notes"] ?? null), "html", null, true);
                    yield "</pre>
      </div>
    ";
                }
                // line 79
                yield "  ";
            }
        } else {
            // line 81
            yield "  <div><b>";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Submission Number"));
            yield ":</b> ";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["serial"] ?? null), "html", null, true);
            yield "</div>
  <div><b>";
            // line 82
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Created"));
            yield ":</b> ";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["created"] ?? null), "html", null, true);
            yield "</div>
";
        }
        // line 84
        yield "
";
        // line 85
        if ((($tmp = ($context["delete"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 86
            yield "  <br/>
  <div>";
            // line 87
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["delete"] ?? null), "html", null, true);
            yield "</div>
";
        }
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["submissions_view", "serial", "sid", "uuid", "uri", "token_view", "token_update", "token_delete", "created", "completed", "changed", "remote_addr", "submitted_by", "language", "is_draft", "current_page", "webform", "submitted_to", "sticky", "locked", "notes", "delete"]);        return; yield;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "modules/contrib/webform/templates/webform-submission-information.html.twig";
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
        return array (  250 => 87,  247 => 86,  245 => 85,  242 => 84,  235 => 82,  228 => 81,  224 => 79,  218 => 76,  213 => 75,  210 => 74,  202 => 72,  199 => 71,  191 => 69,  189 => 68,  186 => 67,  183 => 66,  175 => 64,  173 => 63,  166 => 62,  158 => 60,  156 => 59,  150 => 58,  143 => 56,  137 => 55,  131 => 54,  124 => 52,  118 => 51,  112 => 50,  109 => 49,  101 => 47,  98 => 46,  90 => 44,  87 => 43,  79 => 41,  76 => 40,  68 => 38,  66 => 37,  60 => 36,  54 => 35,  47 => 34,  45 => 33,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "modules/contrib/webform/templates/webform-submission-information.html.twig", "C:\\xampp\\htdocs\\ewa\\modules\\contrib\\webform\\templates\\webform-submission-information.html.twig");
    }
    
    public function ensureSecurityChecked(): void
    {
        if ($this->sandbox->isSandboxed($this->source)) {
            $this->checkSecurity();
        }
    }
    
    public function checkSecurity()
    {
        static $tags = ["if" => 33];
        static $filters = ["t" => 34, "escape" => 34];
        static $functions = [];
        static $tests = [];

        try {
            $this->sandbox->checkSecurity(
                [0 => "if"],
                [0 => "t", 1 => "escape"],
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
