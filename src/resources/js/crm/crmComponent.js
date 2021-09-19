import Vue from "vue";

//Helper for front-end
Vue.component(
    "app-auth-login",
    require("./Component/Views/Auth/Login").default
);
//User Invitation for front-end
Vue.component(
    "complete-user-invitation",
    require("./Component/Views/Auth/CompleteInvitation").default
);
// Password Reset
Vue.component(
    "app-password-reset",
    require("./Component/Views/Auth/PasswordReset").default
);
Vue.component(
    "app-reset-password",
    require("./Component/Views/Auth/ResetPassword").default
);

Vue.component(
    "app-top-navbar",
    require("./Component/Views/Includes/TopNavbar").default
);
Vue.component(
    "app-notification-list",
    require("./Component/Views/Includes/NotificationList").default
);

Vue.component(
    "app-step-input-selector",
    require("./Component/Helpers/StepInputSelector").default
);
Vue.component(
    "app-common-address",
    require("./Component/Helpers/DataTable/AddressHelper").default
);
Vue.component(
    "app-contact-person",
    require("./Component/Views/Contacts/Person/Person").default
);
Vue.component(
    "app-person-modal",
    require("./Component/Views/Contacts/Person/PersonModal").default
);

Vue.component(
    "app-contact-person-details",
    require("./Component/Views/Contacts/Person/PersonDetails").default
);

Vue.component(
    "app-person-import",
    require("./Component/Views/Contacts/Import/PersonImport").default
);

Vue.component(
    "app-person-send-invitation",
    require("./Component/Views/Contacts/Person/SendInvitationModal").default
);

Vue.component(
    "app-organization-import",
    require("./Component/Views/Contacts/Import/OrganizationImport").default
);

Vue.component(
    "app-common-person-org",
    require("./Component/Helpers/DataTable/CommonPeronOrgColumn").default
);
Vue.component(
    "all-person-org",
    require("./Component/Helpers/DataTable/ViewAllPersonOrgModal").default
);

Vue.component(
    "person-email-phone-column",
    require("./Component/Views/Contacts/Person/EmailPhoneColumn").default
);
Vue.component(
    "all-email-phone",
    require("./Component/Views/Contacts/Person/ViewAllEmailPhoneModal").default
);

//organization
Vue.component(
    "app-contact-organization",
    require("./Component/Views/Contacts/Organization/Organization").default
);
Vue.component(
    "app-organization-modal",
    require("./Component/Views/Contacts/Organization/OrganizationModal").default
);
Vue.component(
    "app-organization-view",
    require("./Component/Views/Contacts/Organization/OrganizationView").default
);

// organization Details
Vue.component("app-contact-type-list",
    require("./Component/Views/Contacts/ContactType/ContactTypeList").default
);
Vue.component("app-contact-type-modal",
    require("./Component/Views/Contacts/ContactType/ContactTypeModal").default
);



//Front-end Deals
Vue.component(
    'app-deals-card-view',
    require("./Component/Views/Deals/GridView/DealsGridView").default
);
Vue.component(
    'app-deal-card',
    require("./Component/Views/Deals/GridView/DealCard").default
);
Vue.component(
    "app-deals-list-view",
    require("./Component/Views/Deals/ListView").default
);
Vue.component(
    "app-deal-modal",
    require("./Component/Views/Deals/DealModal").default
);
Vue.component(
    "app-pipeline",
    require("./Component/Views/Deals/Pipeline/Pipelines").default
);
Vue.component(
    "pipeline-column-name",
    require("./Component/Views/Deals/Pipeline/PipelineColumnName").default
);
Vue.component(
    "app-kanban-view",
    require("./Component/Views/Deals/KanbanView").default
);
Vue.component(
    "app-bulk-action",
    require("./Component/Views/Deals/BulkAction").default
);
Vue.component(
    "bulk-action-tag-manager",
    require("./Component/Views/Deals/Details/BulkActionTagManager").default
);
Vue.component(
    "app-move-all-deal-modal",
    require("./Component/Views/Deals/moveAllDealModal").default
);
Vue.component(
    "add-edit-pipeline",
    require("./Component/Views/Deals/Pipeline/AddPipeline").default
);
Vue.component(
    "app-edit-pipeline",
    require("./Component/Views/Deals/Pipeline/EditPipeline").default
);

Vue.component(
    "app-import-pipeline",
    require("./Component/Views/Deals/Import/ImportPipeline").default
);

Vue.component(
    'deal-details',
    require('./Component/Views/Deals/DealDetails').default
);
Vue.component(
    'deal-tag-manage-modal',
    require('./Component/Views/Deals/Details/DealTagManageModal').default
);
Vue.component(
    'deal-title-column',
    require('./Component/Views/Deals/DealTitleColumn').default
);

Vue.component(
    'deal-card-column-manager',
    require('./Component/Helpers/DataTable/CardColumManager').default
);

// Deal Details Page Tabs Components
Vue.component(
    'common-activity-tab',
    require('./Component/Helpers/DetailsActivityTabs/ActivityTab').default
);
Vue.component(
    'common-file-tab',
    require('./Component/Helpers/DetailsActivityTabs/FileTab').default
);
Vue.component(
    'common-note-tab',
    require('./Component/Helpers/DetailsActivityTabs/NoteTab').default
);

// Deal Details Page Tabs Components
Vue.component(
    'deal-activity-tab',
    require('./Component/Views/Deals/ActivityTabs/ActivityTab').default
);
Vue.component(
    'deal-call-tab',
    require('./Component/Views/Deals/ActivityTabs/CallTab').default
);
Vue.component(
    'deal-email-tab',
    require('./Component/Views/Deals/ActivityTabs/EmailTab').default
);
Vue.component(
    'deal-file-tab',
    require('./Component/Views/Deals/ActivityTabs/FileTab').default
);
Vue.component(
    'deal-note-tab',
    require('./Component/Views/Deals/ActivityTabs/NoteTab').default
);

//Deal Details

Vue.component(
    'deal-contact-person',
    require('./Component/Views/Deals/Details/DealsContactPerson').default
);

Vue.component(
    'app-deal-lead-info',
    require('./Component/Views/Deals/Details/DealLeadInfo').default
);

Vue.component(
    'app-deal-activity-collaborator',
    require('./Component/Views/Deals/Details/ActivityCollaborator').default
);

Vue.component(
    "details-tag-manager",
    require("./Component/Helpers/DetailsTags/DetailsTagManager").default
);

Vue.component(
    "icon-with-text",
    require("./Component/Helpers/DataTable/IconWithText").default
);

Vue.component(
    "app-deal-detail",
    require("./Component/Views/Deals/Details/DealDetails").default
);

Vue.component(
    "app-deal-participation-info",
    require("./Component/Views/Deals/Details/ParticipationInfo").default
);

Vue.component(
    "app-deal-organization-info",
    require("./Component/Views/Deals/Details/DealOrganizationInfo").default
);

Vue.component(
    "app-deal-activities-modal",
    require("./Component/Views/Deals/Details/DealActivityModal").default
);

Vue.component(
    "app-dela-note-modal",
    require("./Component/Views/Deals/Details/DealNoteModal").default
);

Vue.component(
    "app-deal-import",
    require("./Component/Views/Deals/Import/ImportDeal").default
);

Vue.component(
    "app-lost-reason-list",
    require("./Component/Views/Deals/LostReasons/LostReasonList").default
);
Vue.component(
    "app-lost-reason-modal",
    require("./Component/Views/Deals/LostReasons/LostReasonModal").default
);

Vue.component(
    "app-deal-lost-confirm-modal",
    require("./Component/Views/Deals/Details/DealLoastConfirmModal").default
);

Vue.component(
    "app-deal-won-confirm-modal",
    require("./Component/Views/Deals/Details/DealWonConfirmModal").default
);

Vue.component(
    "app-deal-participant",
    require("./Component/Views/Deals/Details/ViewAllParticipants").default
);
Vue.component(
    "app-deal-collaborator",
    require("./Component/Views/Deals/Details/ViewAllCollaborators").default
);

Vue.component(
    "deal-send-proposal-modal",
    require("./Component/Views/Deals/Details/SendProposalModal").default
);

Vue.component(
    'deal-details-modal',
    require('./Component/Views/Deals/Details/DetailsDealModal').default
);

Vue.component(
    'deal-quick-view-stage',
    require('./Component/Views/Deals/DealQuickView/StageAddEdit').default
);
Vue.component(
    'deal-quick-view-deal-value',
    require('./Component/Views/Deals/DealQuickView/DealValueAddEdit').default
);
Vue.component(
    'deal-quick-view-lead-info',
    require('./Component/Views/Deals/DealQuickView/LeadInfoAddEdit').default
);
Vue.component(
    'deal-quick-view-expire-date',
    require('./Component/Views/Deals/DealQuickView/ExpireDateAddEdit').default
);
Vue.component(
    'deal-quick-view-tags',
    require('./Component/Views/Deals/DealQuickView/TagManager').default
);
Vue.component(
    'deal-quick-view-followers',
    require('./Component/Views/Deals/DealQuickView/DealFollowers').default
);


// Start Common Component With Helper Folder Load

Vue.component(
    "app-media-name-column",
    require("./Component/Helpers/DataTable/MediaName").default
);

Vue.component(
    "app-details-job-title",
    require("./Component/Helpers/JobTitle/DetailsJobTitle").default
);

Vue.component(
    "app-details-deals",
    require("./Component/Helpers/PersonOrgDeals/PersonOrgDeals.vue").default
);

Vue.component(
    "app-details-information",
    require("./Component/Helpers/Details/DetailsInformation").default
);

Vue.component(
    "app-address-details",
    require("./Component/Helpers/AddressDetails").default
);

Vue.component(
    "app-details-contact-info",
    require("./Component/Helpers/ContactInfo/DetailsContactInfo").default
);

Vue.component(
    "app-follower-details",
    require("./Component/Helpers/Follower/DetailsFollower").default
);

Vue.component(
    "app-user-info-details",
    require("./Component/Helpers/PersonOrgUserInfo/PersonOrgDetailsUserInfo").default
);

// End


//Front-end Proposals
Vue.component(
    'app-proposals-list-view',
    require('./Component/Views/Proposals/ListView').default
);

Vue.component(
    'app-send-proposals',
    require('./Component/Views/Proposals/SendProposal').default
);

Vue.component(
    'app-template-view',
    require('./Component/Views/Proposals/TemplateView').default
);

Vue.component(
    'app-add-template',
    require('./Component/Views/Proposals/AddTemplate').default
);

Vue.component(
    'app-choose-template-modal',
    require('./Component/Views/Proposals/UI/ChooseTemplatesModal').default
);

Vue.component(
    'app-send-proposal-modal',
    require('./Component/Views/Proposals/UI/SendProposalModal').default
);

Vue.component(
    'app-template-preview-modal',
    require('./Component/Views/Proposals/UI/TemplatePreviewModal').default
);

Vue.component(
    'app-proposal-preview-modal',
    require('./Component/Views/Proposals/UI/ProposalPreviewModal').default
);
Vue.component(
    'app-proposal-toggle-button',
    require('./Component/Views/Proposals/UI/ProposalToggleButton').default
);
Vue.component(
    'app-proposal-status',
    require('./Component/Views/Proposals/UI/ProposalStatus').default
);

Vue.component(
    'app-proposal-kanban',
    require('./Component/Views/Proposals/KanbanView').default
);
Vue.component(
    'app-proposal-overview',
    require('./Component/Views/Proposals/ProposalOverview').default
);

//DataTable tag view
Vue.component('tags-type-column',
    require("./Component/Helpers/DataTable/TagsTypeColumn").default);

Vue.component('tags-followers',
    require("./Component/Helpers/FollowerTag").default);


// User And Role
Vue.component(
    "app-users-roles",
    require("./Component/Views/UserRoles/UsersRoles").default
);

Vue.component(
    "image-group",
    require("./Component/Views/UserRoles/ImageGroup").default
);
Vue.component(
    "app-roles-modal",
    require("./Component/Views/UserRoles/roles/RolesModal").default
);
Vue.component(
    "app-user-modal",
    require("./Component/Views/UserRoles/users/UserModal").default
);
Vue.component(
    "app-user-invite-modal",
    require("./Component/Views/UserRoles/UserInviteModal").default
);

Vue.component(
    "app-user-manage-role-modal",
    require("./Component/Views/UserRoles/users/ManageUserRoleModal").default
);


Vue.component(
    "app-manage-users-modal",
    require("./Component/Views/UserRoles/roles/ManageUserModal").default
);

Vue.component(
    "app-view-user-modal",
    require("./Component/Views/UserRoles/roles/ViewModal").default
);


//Activity
Vue.component(
    "app-activities-list-view",
    require("./Component/Views/Activities/ListView").default
);

//Calender view integrated
Vue.component('activity-calendar', require('./Component/Views/Activities/Calendar/ActivityCalendar').default);
Vue.component('calendar-view', require('./Component/Views/Activities/CalendarView').default);

Vue.component(
    "app-activity-modal",
    require("./Component/Views/Activities/ActivityModal").default
);
Vue.component(
    "app-activity-done",
    require("./Component/Views/Activities/ActivityDone").default
);
Vue.component(
    "activity-details-page-link",
    require("./Component/Views/Activities/ActivityDetailsPageLink").default
);

// Common Component

Vue.component(
    "app-common-activity-modal",
    require("./Component/Helpers/CommonActivityModal").default
);
Vue.component(
    "app-common-note-modal",
    require("./Component/Helpers/CommonNoteModal").default
);


Vue.component("app-common-all-deals",
    require("./Component/Helpers/ViewAllDealModal").default
);

Vue.component(
    "app-common-all-follower",
    require("./Component/Helpers/ViewAllFollowerModal").default
);

Vue.component(
    "app-common-activity-show",
    require("./Component/Helpers/DetailsActivityShow/ShowActivityDetails").default
);

Vue.component(
    "details-page-custom-field",
    require("./Component/Helpers/DetailsPageCustomField/CustomFieldManage").default
);

// User Profile
Vue.component(
    "app-my-profile",
    require("./Component/Views/Profile/Profile").default
);
Vue.component(
    "app-profile-personal-info",
    require("./Component/Views/Profile/PersonalInfo").default
);
Vue.component(
    "app-password-change",
    require("./Component/Views/Profile/PasswordChange").default
);
Vue.component(
    "app-activity-log",
    require("./Component/Views/Profile/Activity").default
);
Vue.component(
    "app-profile-social-links",
    require("./Component/Views/Profile/SocialLinks").default
);

// Settings
Vue.component(
    "app-setting-layout",
    require("./Component/Views/Settings/Setting").default
);
Vue.component(
    "app-general-settings",
    require("./Component/Views/Settings/GeneralSetting").default
);

Vue.component(
    "app-custom-field",
    require("./Component/Views/Settings/CustomField").default
);

Vue.component(
    "app-custom-field-modal",
    require("./Component/Views/Settings/CustomFieldModal").default
);

Vue.component(
    "app-email-setup",
    require("./Component/Views/Settings/EmailSetup").default
);

Vue.component(
    "app-broadcast-setup",
    require("./Component/Views/Settings/BroadcastSetup").default
);

Vue.component(
    "app-notification-settings",
    require("./Component/Views/Settings/Notification/NotificationSettings").default
);

Vue.component(
    "app-notification-setting-modal",
    require("./Component/Views/Settings/Notification/NotificationSettingModal").default
);
Vue.component(
    "app-notification-template-setting",
    require("./Component/Views/Settings/Notification/NotificationTemplateSettings").default
);

Vue.component(
    "app-database-template",
    require("./Component/Views/Settings/Notification/Template/DatabaseTemplate").default
);
Vue.component(
    "app-mail-template",
    require("./Component/Views/Settings/Notification/Template/MailTemplate").default
);

Vue.component(
    "app-check-email-deliver",
    require("./Component/Views/Settings/CheckEmailDeliverModal").default
);

//Button submit loader
Vue.component(
    "app-submit-button-loader",
    require("./Component/Helpers/SubmitButtonLoader/Loader").default
);

// for reports
Vue.component('reports-deal', require("./Component/Views/Reports/Deal/ReportDeal").default);

Vue.component('reports-deal-details', require("./Component/Views/Reports/Deal/ReportDealDetails").default);

//Proposal
Vue.component('reports-proposal', require("./Component/Views/Reports/Proposal/ReportProposal").default);
Vue.component('proposal-details', require("./Component/Views/Reports/Proposal/ProposalDetails").default);

//Pipeline
Vue.component('reports-pipeline', require("./Component/Views/Reports/Pipeline/ReportPipeline").default);
Vue.component('reports-pipeline-details', require("./Component/Views/Reports/Pipeline/ReportPipelineDetails").default);

//Dashboard
Vue.component('crm-dashboard', require("./Component/Views/Dashboard/Dashboard").default);

//Export
Vue.component('app-data-export', require("./Component/Helpers/Exports/AppExport").default);

/*setup*/
Vue.component('app-environment', require('./Component/Views/Setup/EnvironmentWizard').default);
Vue.component('app-admin-wizard', require('./Component/Views/Setup/AdminWizard').default);
Vue.component('app-email-setup-wizard', require('./Component/Views/Setup/EmailSetupWizard').default);
Vue.component('app-pusher-setup-wizard', require('./Component/Views/Setup/PusherSetupWizard').default);
Vue.component('app-update', require('./Component/Views/Setup/Update').default);


