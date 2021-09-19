### Person Builder: 
#### person builder API and Resource

Request Type: GET 

URL: ```/crm/person```  

Result:
```json5
[
  {
    "id": 2,
    "name": "NAME_OF_PERSON",
    "address": null,
    "contact_type_id": 1,
    "created_by": {
      "id": 1,
      "first_name": "FIRST_NAME",
      "last_name": "LAST_NAME",
      "full_name": "FULL NAME"
    },
    "owner_id": 1,
    "created_at": "{DATE}",
    "updated_at": "{DATE}",
    "contact_type": {
      "id": 1,
      "name": "Customer"
    },
    "owner": {
      "id": 1,
      "first_name": "FIRST_NAME",
      "last_name": "LAST_NAME",
      "full_name": "FULL NAME"
    },
    "phone": [
      {
        "value": "PHONE_NUMBER",
        "type_id": 1
      },
      //...
    ],
  },
  //...
]
```

Request Type: POST
 
URL: ```/crm/person```  

Body:
```json5
{
  "name": "NAME_OF_PERSON",
  "address": "",
  "contact_type_id": CONTACT_TYPE_ID,
  "owner_id": USER_ID,
  "organization_id": ORGANIZATION_ID,
  "job_title": "JOB_TITLE",
  "phone": [
    {
      "value": "PHONE_NUMBER",
      "type_id": TYPE_ID
    },
    {
      "value": "PHONE_NUMBER",
      "type_id": TYPE_ID
    }
  ],
  "email": [
    {
      "value": "EMAIL_ADDRESS",
      "type_id": TYPE_ID
    }
  ]
}
```

Result: 

```json5
{
  "status": true,
  "message": "Person has been created successfully"
}
```

Request Type: PUT PATCH
 
URL: ```/crm/person/{person}```  

Body:
```json5
{
  "name": "NAME_OF_PERSON",
  "address": "",
  "contact_type_id": CONTACT_TYPE_ID,
  "owner_id": USER_ID,
  "organization_id": ORGANIZATION_ID,
  "job_title": "JOB_TITLE",
  "phone": [
    {
      "value": "PHONE_NUMBER",
      "type_id": TYPE_ID
    },
    {
      "value": "PHONE_NUMBER",
      "type_id": TYPE_ID
    }
  ],
  "email": [
    {
      "value": "EMAIL_ADDRESS",
      "type_id": TYPE_ID
    }
  ]
}
```

Result: 

```json5
{
  "status": true,
  "message": "Person has been updated successfully"
}
```


Request Type: DELETE
 
URL: ```/crm/person/{person}```  

Result: 

```json5
{
  "status": true,
  "message": "Person has been deleted successfully"
}
```


### Organization Builder: 
#### organization builder API and Resource

Request Type: GET 

URL: ```/organizations```  

Result:
```json5
[
  {
    "id": 1,
    "name": "Grant, Gutmann and Grady",
    "address": "8733 Eleanore View\nNorth Shanelle, VA 99109",
    "contact_type_id": 4,
    "created_by": {
      "id": 1,
      "first_name": "Super",
      "last_name": "Admin",
      "full_name": "Super Admin"
    },
    "owner_id": 1,
    "created_at": "2020-05-05T05:16:59.000000Z",
    "updated_at": "2020-05-05T05:16:59.000000Z",
    "contact_type": {
      "id": 4,
      "name": "Cold Lead"
    },
    "owner": {
      "id": 1,
      "first_name": "Super",
      "last_name": "Admin",
      "full_name": "Super Admin"
    }
  },
  //...
]
```

Request Type: POST
 
URL: ```/organizations```  

Body:
```json5
{
    "name": "your name",
    "address" : "your address", // Optional
    "contact_type_id" : 1,
    "created_by" : 1, // Optional
    "owner_id" : 1,
}
```

Result: 

```json5
{
    "status": true,
    "message": "Organization has been created successfully",
  
}
```

```

Request Type: PUT PATCH
 
URL: ```/organizations/{organization}```  

Body:
```json5
{
    "name": "your name",
    "address" : "your address", // Optional
    "contact_type_id" : 1,
    "created_by" : 1, // Optional
    "owner_id" : 1,
}
```

Result: 

```json5
{
    "status": true,
    "message": "Organization has been updated successfully",
    
}
```

```

Request Type: DELETE
 
URL: ```/organizations/{organization}```  

Body:
```json5
{
    "name": "your name",
    "address" : "your address", // Optional
    "contact_type_id" : 1,
    "created_by" : 1, // Optional
    "owner_id" : 1,
}
```

Result: 

```json5
{
    "status": true,
    "message": "Organization has been deleted successfully",
    
}
```


### Pipeline Builder: 
#### Pipeline builder API and Resource

Request Type: GET 

URL: ```/pipelines```  

Result:
```json5
[
    {
       "id": 1,
       "name": "Pipeline name",
       "created_by": 1,
       "total_stages": 6,
       "total_deals": 54,
       "total_deal_value": "2992872"
    },
    //...
]
```

Request Type: POST
 
URL: ```/pipelines```  

Body:
```json5
{
    "name": "your pipeline name",
    "created_by" : 1, // Optional
}
```

Result: 

```json5
{
    "status": true,
    "message": "Pipeline has been created successfully",
  
}
```

```

Request Type: PUT PATCH
 
URL: ```/pipelines/{pipeline}```  

Body:
```json5
{
    "name": "your pipeline update",
    "created_by" : 1, // Optional
    
}
```

Result: 

```json5
{
    "status": true,
    "message": "Pipeline has been updated successfully",
    
}
```

```

Request Type: DELETE
 
URL: ```/pipelines/{pipeline}```  

Body:
```json5
{
          "id": 1,
          "name": "Pipeline name",
          "created_by": 1,
          "total_stages": 6,
          "total_deals": 54,
          "total_deal_value": "2992872"
}
```

Result: 

```json5
{
    "status": true,
    "message": "Pipeline has been deleted successfully",
    
}
```

### Stages Builder: 
#### Stages builder API and Resource

Request Type: GET 

URL: ```/stages```  

Result:
```json5
[
    {
      "id": 1,
      "name": "Lead In",
      "probability": 100,
      "pipeline_id": 1,
      "created_by": {
          "id": 1,
          "first_name": "Super",
          "last_name": "Admin",
          "full_name": "Super Admin"
      },
      "pipeline": {
          "id": 1,
          "name": "Testing",
          "total_stages": 6,
          "total_deals": 54,
          "total_deal_value": "2992872"
      }
    },
    //...
]
```

Request Type: POST
 
URL: ```/stages```  

Body:
```json5
{
   	"name":"stage name",
   	"pipeline_id":1,
    "created_by" : 1, // Optional
}
```

Result: 

```json5
{
    "status": true,
    "message": "Stage has been created successfully",
  
}
```

Request Type: PUT PATCH
 
URL: ```/stages/{stage}```  

Body:
```json5
{
  "name": "stage Update",
  "pipeline_id": 1,
  "created_by": 1, //Optional
}
```

Result: 

```json5
{
    "status": true,
    "message": "Stages has been updated successfully",
    
}
```


Request Type: DELETE
 
URL: ```/stages/{stages}```  

Body:
```json5
{
          "id": 1,
                "name": "Lead In",
                "probability": 100,
                "pipeline_id": 1,
                "created_by": {
                    "id": 1,
                    "first_name": "Super",
                    "last_name": "Admin",
                    "full_name": "Super Admin"
                },
                "pipeline": {
                    "id": 1,
                    "name": "Testing",
                    "total_stages": 6,
                    "total_deals": 54,
                    "total_deal_value": "2992872"
                }
}
```

Result: 

```json5
{
    "status": true,
    "message": "Stages has been deleted successfully",
    
}
```

### Lost Reason Builder: 
#### Lost Reason builder API and Resource

Request Type: GET 

URL: ```/deal/lost-reasons```  

Result:
```json5
[
    {
        "id": 1,
        "lost_reason": "Miscommunication",
        "created_by": {
            "id": 1,
            "first_name": "Super",
            "last_name": "Admin",
            "full_name": "Super Admin"
        },
    },
    //...
]
```

Request Type: POST
 
URL: ```/deal/lost-reasons```  

Body:
```json5
{
   	"lost_reason":"lost reason name",
    "created_by" : 1, // Optional
}
```

Result: 

```json5
{
    "status": true,
    "message": "Lost reason has been created successfully",
  
}
```


Request Type: PUT PATCH
 
URL: ```/deal/lost-reasons/{lostReason}```  

Body:
```json5
{
   	   	"lost_reason":"lost reason name Update",
         "created_by" : 1, // Optional
    
}
```

Result: 

```json5
{
    "status": true,
    "message": "Lost reason has been updated successfully",
    
}
```


Request Type: DELETE
 
URL: ```/deal/lost-reasons/{lostReason}```  

Body:
```json5
{
    "id": 1,
            "lost_reason": "Miscommunication",
            "created_by": {
                "id": 1,
                "first_name": "Super",
                "last_name": "Admin",
                "full_name": "Super Admin"
            },
}
```

Result: 

```json5
{
    "status": true,
    "message": "Lost reason has been deleted successfully",
    
}
```

### Activity Types Builder: 
#### Activity Types builder API and Resource

Request Type: GET 

URL: ```/crm/activity_types```  

Result:
```json5
[
  {
    "id": 1,
    "name": "Call"
  },
  {
    "id": 2,
    "name": "Meeting"
  },
  {
    "id": 3,
    "name": "Email"
  }
  //...
]
//...
```

Request Type: POST
 
URL: ```/crm/activity_types```  

Body:
```json5
{
  "name" : "NAME_OF_ACTIVITY_TYPE",
  "created_by" : "AUTH_USER_ID", // Optional
}
```

Result: 

```json5
{
  "status": true,
  "message": "Activity Type has been created successfully",
  "activity_type": {
    "name": "NAME_OF_ACTIVITY_TYPE",
    "updated_at": "xxxx-xx-xxxxx:xx:xx.xxxxxx",
    "created_at": "xxxx-xx-xxxxx:xx:xx.xxxxxx",
    "id": "ID_OF_RECENTLY_CREATED_ACTIVITY_TYPE"
  }
}
```


Request Type: PUT PATCH
 
URL: ```/crm/activity_types/{activity_type}```  

Body:
```json5
{
  "name" : "NAME_OF_ACTIVITY_TYPE_TO_UPDATE",
  "created_by" : "AUTH_USER_ID", // Optional
}
```

Result: 

```json5
{
  "status": true,
  "message": "Activity Type has been updated successfully",
  "activity_type": {
    "name": "NAME_OF_ACTIVITY_TYPE_TO_UPDATE",
    "updated_at": "xxxx-xx-xxxxx:xx:xx.xxxxxx",
    "created_at": "xxxx-xx-xxxxx:xx:xx.xxxxxx",
    "id": "ID_OF_RECENTLY_UPDATED_ACTIVITY_TYPE"
  }
}
```

Request Type: DELETE
 
URL: ```/crm/activity_types/{activity_type}```  

Result: 

```json5
{
  "status": true,
  "message": "Activity Type has been deleted successfully"
}
```

### Lost Reason Builder: 
#### Lost Reason builder API and Resource

Request Type: GET 

URL: ```/crm/deal/lost-reasons```  

Result:
```json5
[
        {
            "id": 1,
            "lost_reason": "Miscommunication",
            "created_by": {
                "id": 1,
                "first_name": "Super",
                "last_name": "Admin",
                "full_name": "Super Admin"
            },
            "created_at": null,
            "updated_at": null
        },
        //...
    ]
//...
```

Request Type: POST
 
URL: ```/crm/deal/lost-reasons```  

Body:
```json5
{
  "lost_reason" : "NAME_OF_LOST_REASON",
  "created_by" : "AUTH_USER_ID", // Optional
}
```

Result: 

```json5
{
  "status": true,
  "message": "Lost reason has been created successfully",
}
```


Request Type: PUT PATCH
 
URL: ```/crm/deal/lost-reasons/{lost_reason}```  

Body:
```json5
{
  "lost_reason" : "NAME_OF_LOST_REASON_TO_UPDATE",
  "created_by" : "AUTH_USER_ID", // Optional
}
```

Result: 

```json5
{
  "status": true,
  "message": "Lost reason has been updated successfully",
}
```

Request Type: DELETE
 
URL: ```/crm/deal/lost-reasons/{lost_reason}```  

Result: 

```json5
{
  "status": true,
  "message": "Lost reason has been deleted successfully"
}
```


###  Contact Type Builder: 
#### Contact Type builder API and Resource

Request Type: GET 

URL: ```/crm/contact/types``` 

Result:
```json5
[

        {
           "id": 1,
           "name": "your contact type",
           "created_at": "2020-05-10T07:49:53.000000Z",
           "updated_at": "2020-05-10T07:49:53.000000Z"
        },

        //...
    ]
//...
``` 
Request Type: POST
URL: ```/crm/contact/types```  

Body:
```json5
{
   "name": "your contact type",
}
```

Result: 

```json5
{
  "status": true,
  "message": "Contact type has been created successfully",
}
```

Request Type: PUT PATCH

URL: ```/crm/contact/types/{type}```  
 
Body:
```json5
{

  "name": "your contact type update",
}
```

Result: 

```json5
{
  "status": true,
  "message": "Contact type has been updated successfully",
}
```

Request Type: DELETE
 
URL: ```/crm/contact/types/{type}```  
 
Result: 

```json5
{
  "status": true,
  "message": "Contact type has been deleted successfully"
}
```

### Tags Builder: 
#### Tags builder API and Resource

Request Type: GET 

URL: ```/crm/tags```  
Result:
```json5
[
    {
        "id": 1,
        "name": "enim",
        "color_code": "#1500d2",
        "taggable_type": "App\\Models\\CRM\\Deal\\Deal",
        "taggable_id": 47,
        "created_at": "2020-05-10T05:04:21.000000Z",
        "updated_at": "2020-05-10T05:04:21.000000Z"
     },

        //...
    ]
//...
```

Request Type: POST
URL: ```/crm/tags```  

Body:
```json5
{
  "name" : "your tags name",
  "color_code" : "#ddd", // Optional
}
```

Result: 

```json5
{
  "status": true,
  "message": "Tags has been created successfully",
}
```


Request Type: PUT PATCH

URL: ```/crm/tags/{tag}```  

Body:
```json5
{

  
   "name" : "your tags update",
   "color_code" : "#ddd", // Optional

}
```

Result: 

```json5
{
  "status": true,
  "message": "Tags has been updated successfully",
}
```

Request Type: DELETE
 
URL: ```/crm/tags/{tag}```  

Result: 

```json5
{
  "status": true,
  "message": "Tags has been deleted successfully"
}
```

### Activity Builder: 
#### Activity builder API and Resource

Request Type: GET 

URL: ```/crm/activities```  
Result:
```json5
[
    {
           "id": 1,
            "title": "Kamron Parisian sent an Call to Dr. Alex Emmerich at orlando.fahey@example.com",
            "activity_type_id": 1,
            "contextable_id": 21,
            "created_by": {
                "id": 1,
                "first_name": "Super",
                "last_name": "Admin",
                "full_name": "Super Admin"
            },
            "status_id": 3,
            "started_at": null,
            "ended_at": "2006-07-25 20:07:37",
            "created_at": "2020-05-10T05:04:20.000000Z",
            "updated_at": "2020-05-10T05:04:20.000000Z",
            "activity_type": {
                "id": 1,
                "name": "Call"
            }
     },

        //...
    ]
//...
```

Request Type: POST
URL: ```/crm/activities```  

Body:
```json5
{
  "title" : "your activities title",
  "activity_type_id" : "1", // Optional
  "contextable_id" : "1", // Optional
  "created_by" : "1", // Optional
  "status_id" : "1", // Optional
  "started_at" : "xxxx-xx-xx", // Optional
  "ended_at" : "xxxx-xx-xx", // Optional
}
```

Result: 

```json5
{
  "status": true,
  "message": "Activity has been created successfully",
}
```


Request Type: PUT PATCH

URL: ```/crm/activities/{activitie}```  

Body:
```json5
{

  
    "title" : "your activities title update",
    "activity_type_id" : "1", // Optional
    "contextable_id" : "1", // Optional
    "created_by" : "1", // Optional
    "status_id" : "1", // Optional
    "started_at" : "xxxx-xx-xx", // Optional
    "ended_at" : "xxxx-xx-xx", // Optional

}
```

Result: 

```json5
{
  "status": true,
  "message": "Activity has been updated successfully",
}
```

Request Type: DELETE
 
URL: ```/crm/activities/{activitie}```  

Result: 

```json5
{
  "status": true,
  "message": "Activity has been deleted successfully"
}
```
###  Deal: 
#### API


Request Type: `POST`
URL: `/crm/deals`
Body Data Type: `form-data`
Return Type: `json`

Sample Body:

```
  {
    "title":"Demo Create Deal title",
    "organization_id":4,
    "person_id":1,
    "value":34,
    "pipeline_id":2,
    "stage_id":3,
    "lost_reason_id":1,
    "status_id":1,
    "expired_at":"2020-12-04",
    "owner_id":1
  }

```
Success:

```
  {
    "status": true,
    "message": "Deal has been created successfully"
  }
```

Failed:
```
  {
    "status": false,
    "message": "Something went wrong"
  }
```

### Read:

Request Type: `GET`
URL: `/crm/deals/{id}`
Return Type: `Array Object`


Result:

```
[ {
    "id": 4,
    "title": "Sit aut sit accusantium ipsa et.",
    "value": 78504,
    "pipeline_id": 1,
    "stage_id": 4,
    "lost_reason_id": 1,
    "status_id": 1,
    "created_by": {...},
    "owner_id": 1,
    "person_id": 1,
    "organization_id": 2,
    "expired_at": "1972-03-08 06:07:58",
    "created_at": "2020-05-12T07:58:49.000000Z",
    "updated_at": "2020-05-12T07:58:49.000000Z",
    "pipeline": {...},
    "stage": {...},
    "lost_reason": {...},
    "status": {...},
    "person": {...},
    "organization": {...},
    "owner": {...},
    "followers": [...],
    "participents": [...],
    "activity": [...],
    "tag": [...]
  }
  //...
]

```

### Upate:
Request Type: `PUT` `PATCH`
URL: `/crm/deals/{id}`
Body Data Type: `form-data`
Return Type: `json`

Sample Body:

```
  {
    "title":"Demo Update Deal title",
    "organization_id":4,
    "person_id":1,
    "value":34,
    "pipeline_id":2,
    "stage_id":2,
    "lost_reason_id":1,
    "status_id":1,
    "expired_at":"2020-12-04",
    "owner_id":1,
    "_method":"PUT"
  }

```
Success:
```
  {
    "status": true,
    "message": "Deal has been updated successfully"
  }
```

Failed:
```
  {
    "status": false,
    "message": "Something went wrong"
  }
  
```

### Delete:

Request Type: `DELETE`
URL: `/crm/deals/{id}`
Return Type: `json`

Success:

```
  {
    "status": true,
    "message": "Deal has been deleted successfully"
  }

```

Failed:
```
  {
    "status": false,
    "message": "Something went wrong"
  }

```

### Proposal Builder: 
#### Proposal builder API and Resource

Request Type: GET 

URL: ```/crm/proposals```  
Result:
```json5
[
    {
         "id": 1,
         "title": "Quae unde eum voluptas dolore.",
         "content": "Culpa neque quia commodi at et velit quia sunt porro omnis maiores et quia vero.",
         "status_id": 3,
         "deal_id": 36,
         "owner_id": 2,
         "created_by": {
             "id": 1,
             "first_name": "Super",
             "last_name": "Admin",
             "full_name": "Super Admin"
         },
         "expired_at": null,
         "status": {
             "id": 3,
             "name": "status_invited",
             "class": "purple",
             "type": "user",
             "created_at": null,
             "updated_at": null,
             "translated_name": "default.status_invited"
         },
         "deal": {
             "id": 36
         },
         "owner": {
             "id": 2,
             "first_name": "General",
             "last_name": "Admin",
             "full_name": "General Admin"
         }
     },

        //...
    ]
//...
```

Request Type: POST
URL: ```/crm/proposals```  

Body:
```json5
{
  "title" : "your title",
  "content" : "your content here",
  "status_id" : "1",
  "deal_id" : "1", // Optional
  "owner_id" : "1", // Optional
  "created_by" : "1", // Optional
  "expired_at" : "xxxx-xx-xx", // Optional
}
```

Result: 

```json5
{
  "status": true,
  "message": "Proposal has been created successfully",
}
```


Request Type: PUT PATCH

URL: ```/crm/proposals/{proposal}```  

Body:
```json5
{

  
  "title" : "your title update",
    "content" : "your content here update",
    "status_id" : "1",
    "deal_id" : "1", // Optional
    "owner_id" : "1", // Optional
    "created_by" : "1", // Optional
    "expired_at" : "xxxx-xx-xx", // Optional

}
```

Result: 

```json5
{
  "status": true,
  "message": "Proposal has been updated successfully",
}
```

Request Type: DELETE
 
URL: ```/crm/proposals/{proposal}```  

Result: 

```json5
{
  "status": true,
  "message": "Proposal has been deleted successfully"
}
```
### Status API: 

Request Type: GET 

URL: `crm/statuses`

Result:

```
[
  {
    "id":1
    "name":"status_active"
    "class":"success"
    "type":"user"
    "created_at":null
    "updated_at":null
  }
  //...
]

```

Request Type: POST
URL: `/crm/statuses`  

Body:
```
{
  "name":"status_active"
  "class":"success"
  "type":"user"
}
```

Result: 

```
{
  "status": true,
  "message": "status has been created successfully",
}
```


Request Type: PUT PATCH

URL: ```/crm/statuses/{id}```  

Body:

```json5
  {
    "name":"status_active"
    "class":"success"
    "type":"user"
  }
```

Result: 

```json5
{
  "status": true,
  "message": "Status has been updated successfully",
}
```

Request Type: DELETE
 
URL: ```/crm/statuses/{id}```  

Result: 

```json5
{
  "status": true,
  "message": "Status has been deleted successfully"
}
```

### Send Proposal Builder: 
#### Send Proposal builder API and Resource


Request Type: POST

URL: ```/crm/send-proposal/```  

Body:
```json5
{
    "deal_id" : 1,
}
```
Result: 

```json5
{
  "status": true,
  "message": "Proposal email send successfully",
}
```

### Organizations Tags synchronization Builder: 
#### Organizations Tags synchronization builder API and Resource


Request Type: POST

URL: ```/crm/organizations/tags/{organizations}```  

Body:
```json5
{
    "tag_id" : 1 // unique
}
```
Result: 

```json5
{
  "status": true,
  "message": "Tags has been created successfully",
}
```

### Organizations Tags synchronization Builder: 
   #### Organizations Tags synchronization builder API and Resource
   
   
   Request Type: POST
   
   URL: ```/crm/persons/tags/{persons}```  
   
   Body:
   ```json5
   {
       "tag_id" : 1 // unique 
   }
   ```
   Result: 
   
   ```json5
   {
     "status": true,
     "message": "Tags has been created successfully",
   }
   ```

### Organizations Person and Job Title synchronization Builder: 
#### Organizations Person and Job Title synchronization builder API and Resource


Request Type: POST

URL: ```/crm/organizations/sync/{organization}```  

Body:
```json5
{
  "data" : [
    {
      "person_id" : 40,
      "job_title" : "Concept Designer"
    },
    {
      "person_id" : 30,
      "job_title" : "3D Designer"
    },
    {
      "person_id" : 22,
      "job_title" : "Designer"
    }
  ]
}
```
Result: 

```json5
{
  "status": true,
  "message": "Synchronization has been updated successfully",
}
```

### Person followers synchronization Builder: 
#### Person followers synchronization builder API and Resource


Request Type: POST

URL: ```/crm/persons/followers/{person}```  

Body:
```json5
{
   "follower_id":[1,2,3,4,5]
}
```
Result: 

```json5
{
  "status": true,
  "message": "Synchronization has been updated successfully",
}
```

### Organizations followers synchronization Builder: 
#### Organizations followers synchronization builder API and Resource


Request Type: POST

URL: ```/crm/organizations/followers/{organization}```  

Body:
```json5
{
   "person_id":[1,2,3,4,5]
}
```
Result: 

```json5
{
  "status": true,
  "message": "Synchronization has been updated successfully",
}
```

### Person contact synchronization Builder: 
#### Person contact synchronization builder API and Resource


Request Type: POST

URL: ```/crm/persons/contact/sync/{person}```  

Body:
```json5
{
    "phone": [
       {
         "value": "xxxx-xxxx",
         "type_id": 1
       },
       {
         "value": "xxxx-xxxxx",
         "type_id": 2
       }
     ],
     "email": [
       {
         "value": "your@demo.com",
         "type_id": 1
       },
        {
         "value": "demo@demo.com",
         "type_id": 1
       }
     ]
}
```
Result: 

```json5
{
  "status": true,
  "message": "Synchronization has been updated successfully",
}
```
