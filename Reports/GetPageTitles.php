<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */
namespace Piwik\Plugins\FiestaPlugin\Reports;

use Piwik\Piwik;
use Piwik\Plugin\ViewDataTable;
use Piwik\API\Request;
use Piwik\Plugins\Actions\Columns\PageTitle;
use Piwik\Plugins\Actions\Columns\Metrics\AveragePageGenerationTime;
use Piwik\Plugins\Actions\Columns\Metrics\AverageTimeOnPage;
use Piwik\Plugins\Actions\Columns\Metrics\BounceRate;
use Piwik\Plugins\Actions\Columns\Metrics\ExitRate;
use Piwik\Plugin\ReportsProvider;
use Piwik\Report\ReportWidgetFactory;
use Piwik\Widget\WidgetsList;

class GetPageTitles extends Base
{
    protected function init()
    {
        parent::init();

        $this->dimension     = new PageTitle();
        $this->name          = Piwik::translate('Fiesta Visits');
        $this->documentation = Piwik::translate('Actions_PageTitlesReportDocumentation',
                                                array('<br />', htmlentities('<title>')));

        $this->order   = 5;
        $this->metrics = array('nb_hits');

        $this->actionToLoadSubTables = $this->action;
        $this->subcategoryId = 'Fiesta';
    }

    public function configureWidgets(WidgetsList $widgetsList, ReportWidgetFactory $factory)
    {
        // we have to do it manually since it's only done automatically if a subcategoryId is specified,
        // we do not set a subcategoryId since this report is not supposed to be shown in the UI
        $widgetsList->addWidgetConfig($factory->createWidget());
    }

    public function getMetrics()
    {
        $metrics = parent::getMetrics();
        $metrics['nb_visits'] = Piwik::translate('General_ColumnUniquePageviews');

        return $metrics;
    }

    protected function getMetricsDocumentation()
    {
        $metrics = parent::getMetricsDocumentation();
        $metrics['nb_visits']   = Piwik::translate('General_ColumnUniquePageviewsDocumentation');
        $metrics['bounce_rate'] = Piwik::translate('General_ColumnPageBounceRateDocumentation');

        return $metrics;
    }

    public function configureView(ViewDataTable $view)
    {
        $view->config->self_url = Request::getCurrentUrlWithoutGenericFilters(array(
            'module' => $this->module,
            'action' => 'getPageTitles',
        ));

        $view->config->title = $this->name;

        $view->config->addTranslation('label', $this->dimension->getName());
        $view->config->columns_to_display = array('label', 'nb_hits');
        $view->config->addTranslation('label', "fiesta");
        $this->addPageDisplayProperties($view);
        $this->addBaseDisplayProperties($view);
        $view->config->show_search = false;
        $view->config->show_table = false;
        $view->config->show_exclude_low_population = false;
        $view->config->show_export_as_rss_feed = false;
    }

    public function getRelatedReports()
    {
        return array();
    }
}
