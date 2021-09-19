<?php

return [
    'default_stages' => [
        ['name' => 'Lead generation',  'probability' => 100, 'priority' => 0, 'created_by' => 1],
        ['name' => 'Lead nurturing',  'probability' => 100, 'priority' => 1, 'created_by' => 1],
        ['name' => 'Marketing qualified lead',  'probability' => 100, 'priority' => 2, 'created_by' => 1],
        ['name' => 'Sales accepted lead',  'probability' => 100, 'priority' => 3, 'created_by' => 1],
        ['name' => 'Sales qualified lead',  'probability' => 100, 'priority' => 4, 'created_by' => 1],
        ['name' => 'Closed deal',  'probability' => 100, 'priority' => 4, 'created_by' => 1],
        ['name' => 'Post-sale',  'probability' => 100, 'priority' => 4, 'created_by' => 1],
    ],

    'demo_stages' => [
        ['name' => 'Prospecting and Initial Contact',  'probability' => 100, 'pipeline_id' => 1, 'priority' => 0, 'created_by' => 1],
        ['name' => 'Qualifying',  'probability' => 100, 'pipeline_id' => 1, 'priority' => 1, 'created_by' => 1],
        ['name' => 'Needs Assessment',  'probability' => 100, 'pipeline_id' => 1, 'priority' => 2,	'created_by' => 1],
        ['name' => 'Sales Pitch or Product Demo',  'probability' => 100, 'pipeline_id' => 1, 'priority' => 3, 'created_by' => 1],
        ['name' => 'Proposal and Handling Objections',  'probability' => 100, 'pipeline_id' => 1, 'priority' => 4, 'created_by' => 1],
        ['name' => 'Closing',  'probability' => 100, 'pipeline_id' => 1, 'priority' => 5, 'created_by' => 1],
        ['name' => 'Following Up, Repeat Business & Referrals',  'probability' => 100, 'pipeline_id' => 1, 'priority' => 6, 'created_by' => 1],

        ['name' => 'Prospecting and qualifying',  'probability' => 100, 'pipeline_id' => 2, 'priority' => 0, 'created_by' => 2],
        ['name' => 'Preparation/pre-approach',  'probability' => 100, 'pipeline_id' => 2, 'priority' => 1, 'created_by' => 2],
        ['name' => 'Approach',  'probability' => 100, 'pipeline_id' => 2, 'priority' => 2,	'created_by' => 2],
        ['name' => 'Presentation',  'probability' => 100, 'pipeline_id' => 2, 'priority' => 3, 'created_by' => 2],
        ['name' => 'Overcoming objections',  'probability' => 100, 'pipeline_id' => 2, 'priority' => 4, 'created_by' => 2],
        ['name' => 'Closing the sale',  'probability' => 100, 'pipeline_id' => 2, 'priority' => 5, 'created_by' => 2],
        ['name' => 'Following Up',  'probability' => 100, 'pipeline_id' => 2, 'priority' => 6, 'created_by' => 2],

        ['name' => 'Initial Contact & Rapport Building',  'probability' => 100, 'pipeline_id' => 3, 'priority' => 0, 'created_by' => 2],
        ['name' => 'Needs Discovery',  'probability' => 100, 'pipeline_id' => 3, 'priority' => 1, 'created_by' => 2],
        ['name' => 'Offer a Solution',  'probability' => 100, 'pipeline_id' => 3, 'priority' => 2,	'created_by' => 2],
        ['name' => 'Handle Objections & Close the Sale',  'probability' => 100, 'pipeline_id' => 3, 'priority' => 3, 'created_by' => 2],
        ['name' => 'Follow up',  'probability' => 100, 'pipeline_id' => 3, 'priority' => 4, 'created_by' => 2],

        ['name' => 'Initial Contact & Rapport Building',  'probability' => 100, 'pipeline_id' => 4, 'priority' => 0, 'created_by' => 2],
        ['name' => 'Needs Discovery',  'probability' => 100, 'pipeline_id' => 4, 'priority' => 1, 'created_by' => 2],
        ['name' => 'Offer a Solution',  'probability' => 100, 'pipeline_id' => 4, 'priority' => 2,	'created_by' => 2],
        ['name' => 'Handle Objections & Close the Sale',  'probability' => 100, 'pipeline_id' => 4, 'priority' => 3, 'created_by' => 2],
        ['name' => 'Follow up',  'probability' => 100, 'pipeline_id' => 4, 'priority' => 4, 'created_by' => 2]
    ],

    'lost_reasons' => [
        ['lost_reason' => 'Miscommunication',	'created_by' => 1],
        ['lost_reason' => 'Price Quotation',	'created_by' => 1],
        ['lost_reason' => 'Proposal Failed',	'created_by' => 1],
        ['lost_reason' => 'Lack of performance',	'created_by' => 1],
    ],

    'contact_types' => [
        ['name' => 'Customer', 'class' => 'success'],
        ['name' => 'Hot Lead', 'class' => 'danger'],
        ['name' => 'Warm Lead', 'class' => 'purple'],
        ['name' => 'Cold Lead', 'class' => 'warning'],
    ],

    'activity_types' => [
        ['name' => 'Call', 'icon' => 'phone-call'],
        ['name' => 'Meeting', 'icon' => 'users'],
        ['name' => 'Email', 'icon' => 'mail'],
        ['name' => 'Task', 'icon' => 'credit-card'],
        ['name' => 'Deadline', 'icon' => 'calendar'],
        ['name' => 'Others', 'icon' => 'cpu'],
    ],

    'phone_email_types' => [
        ['name' => 'Work', 'class' => 'success'],
        ['name' => 'Home', 'class' => 'info'],
        ['name' => 'Office', 'class' => 'warning'],
    ],

    'icon' => [
        'for_file_icon' => 'paperclip',
        'for_note_icon' => 'file-text',
    ],
];
