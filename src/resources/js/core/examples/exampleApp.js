import Vue from 'vue';

// Test components
Vue.component('test-note', require('./note/TestNote').default);
Vue.component('test-badge', require('./badge/TestBadge').default);
Vue.component('test-breadcrumb', require('./breadcrumb/TestBreadcrumb').default);
Vue.component('test-sidebar', require('./sidebar/TestSidebar').default);
Vue.component('test-navbar', require('./navbar/TestNavbar').default);
Vue.component('test-filter', require('./filters/Example').default);

Vue.component('test-table', require('./datatable/Example').default);
Vue.component('expandable-view', require('./datatable/ExpandableView').default);
Vue.component('test-table-new', require('./datatable/NewExampleWithoutWrapper').default);
Vue.component('test-modal', require('./datatable/TestModal').default);
Vue.component('test-editor', require('./input/TestEditor').default);
Vue.component('test-multi-select', require('./input/TestMultiSelect').default);
Vue.component('test-avatar', require('./avatar/TestAvatar').default);
Vue.component('test-avatars-group', require('./avatars-group/TestAvatarGroup').default);
Vue.component('test-tab', require('./tabs/TestTab').default);
Vue.component('test-form-wizard', require('./form-wizard/Example').default);
Vue.component('wizard-details', require('./form-wizard/test-component/WizardDetails').default);
Vue.component('wizard-delivery', require('./form-wizard/test-component/WizardDelivery').default);
Vue.component('wizard-template', require('./form-wizard/test-component/WizardTemplate').default);
Vue.component('wizard-setup', require('./form-wizard/test-component/WizardSetup').default);
Vue.component('wizard-audience', require('./form-wizard/test-component/WizardAudience').default);
Vue.component('test-preview-card', require('./preview-card/TestPreviewCard').default);
Vue.component('test-card-view', require('./card-view/TestCardView').default);
Vue.component('test-form', require('./form/TestForm').default);
Vue.component('test-toaster', require('./toaster/ToasterExample').default);
Vue.component('test-error', require('./error/ErrorExample').default);
Vue.component('test-calendar-view', require('./calendar/TestCalendarView').default);


// Chart Test Components
Vue.component('bubble-test', require('./charts/BubbleTest').default);
Vue.component('chart-test', require('./charts/ChartTest').default);
Vue.component('custom-line-test', require('./charts/CustomLineTest').default);
Vue.component('doughnut-test', require('./charts/DoughnutTest').default);
Vue.component('horizontal-line-test', require('./charts/HorizontalLineTest').default);
Vue.component('line-test', require('./charts/LineTest').default);
Vue.component('polar-test', require('./charts/PolarTest').default);
Vue.component('pie-test', require('./charts/PiechartTest').default);
Vue.component('reader-test', require('./charts/ReaderchartTest').default);
Vue.component('scatter-test', require('./charts/ScatterTest').default);

// Buttons Test Components
Vue.component('dropdown-menu-test', require('./buttons/dropdown-menu/DropdownMenuTest').default);
Vue.component('app-button-test', require('./buttons/app-button/AppButtonTest').default);
Vue.component('pagination-test', require('./buttons/pagination/PaginationTest').default);

// Widget Test Components
Vue.component('test-widget-without-icon', require('./dashboard-widget/TestWidgetWithoutIcon').default);
Vue.component('test-widget-with-icon', require('./dashboard-widget/TestWidgetWithIcon').default);
Vue.component('test-widget-with-circle', require('./dashboard-widget/TestWidgetWithCircle').default);

// Modal component
Vue.component('example-modal', require('./modal/Example').default);

// Organization Chart
Vue.component('test-organization-chart', require('./organization-chart/Example').default);

// Tag Manager
Vue.component('test-tag-manager', require('./tag/TestTagManager').default);
