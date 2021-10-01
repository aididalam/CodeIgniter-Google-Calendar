# Google Calander Library for CodeIgniter

Manage google calander events.


### Features
------------
- Read Events
- Update Event
- Delete Event
- Create Event

### Setup

------------

#### Setup credentials to communicate with Google Calendar
- The first step is to obtain credentials to access Google's API. I'm going to assume you already have a Google account and are logged in. Select a project in the [Google API console](https://console.cloud.google.com/apis "Google API console") clicking "Select a project."

![](https://github.com/aididalam/images-for-rep/raw/main/CI-Google-Calander/1.png)


- Click on **ENABLE APIS AND SERVICES**

![](https://github.com/aididalam/images-for-rep/raw/main/CI-Google-Calander/2.png)


- Search for Calander

![](https://github.com/aididalam/images-for-rep/raw/main/CI-Google-Calander/3.png)


- Chose **GOOGLE Calander API**

![](https://github.com/aididalam/images-for-rep/raw/main/CI-Google-Calander/4.png)


- Enable Api

![](https://github.com/aididalam/images-for-rep/raw/main/CI-Google-Calander/5.png)


- Go back to dashboard and Click on Credentials

![](https://github.com/aididalam/images-for-rep/raw/main/CI-Google-Calander/6.png)


- Click On Create Crendentials and chose Service Account

![](https://github.com/aididalam/images-for-rep/raw/main/CI-Google-Calander/7.png)


- Fill up details

![](https://github.com/aididalam/images-for-rep/raw/main/CI-Google-Calander/8.png)


- Finish the setup by clicking on done

![](https://github.com/aididalam/images-for-rep/raw/main/CI-Google-Calander/9.png)


- Click on newly Create Service Account
**Note the email for nex step. You have to use this email to manage Calander**

![](https://github.com/aididalam/images-for-rep/raw/main/CI-Google-Calander/10.png)


- Click on keys

![](https://github.com/aididalam/images-for-rep/raw/main/CI-Google-Calander/11.png)


- Click on Add new key

![](https://github.com/aididalam/images-for-rep/raw/main/CI-Google-Calander/12.png)


- Chose Json and Download the key

![](https://github.com/aididalam/images-for-rep/raw/main/CI-Google-Calander/13.png)


------------

#### Create google calander and give access to service account:
- Go to Google Calander and Create a new Calander or chose a Calander.

![](https://github.com/aididalam/images-for-rep/raw/main/CI-Google-Calander/14.png)


- Scroll down to Share with specific people

![](https://github.com/aididalam/images-for-rep/raw/main/CI-Google-Calander/15.png)


- Click on add people and Add the Service account email ID which you copied or get it from the JSON File

![](https://github.com/aididalam/images-for-rep/raw/main/CI-Google-Calander/16.png)


- Finally Note Down Calander ID

![](https://github.com/aididalam/images-for-rep/raw/main/CI-Google-Calander/17.png)


------------

### Setup Library

------------

- Install Google App Client via composer:
`composer require google/apiclient:^2.11`
- Download this library by [clicking here](https://github.com/aididalam/CodeIgniter-Google-Calander-Library/archive/refs/tags/v2.zip "clicking here")
- Extract the library to your project folder.
- Open Application/config/gcalander.php file
- Add Calander ID and Json File Location (which you downloaded when you were generating Service account in the first step) .
(I recomend you to put the JSON file in calendarData Folder)
```php
$config['calendarId'] = "CALENDAR_ID";
$config['calendar_json_path'] = "SERVICE_ACCOUNT_JSON_FILE";//eg calendarData/calendarAPI.json
```

### Usage

------------

**First Call The Library in Controller**

``$this->load->library("googlecalendar");``

- **Load Events**
```php
$this->googlecalendar->getAll();
```
- **Load Specific Event**
```php
$this->googlecalendar->find("event_id");
```

- **Query Event**
```php
$this->googlecalendar->where("key1","value1")->where("key2","value2")->get();
```
- **Update Event**
```php
$this->googlecalendar->find("event_id");
$event->setSummary('Appointment at Somewhere');
$event->setLocation('Bangladesh');
$this->googlecalendar->update($event);
```

- **Delete Event**
```php
$this->googlecalendar->delete("event_id");
```
- **Create Event**
```php
$data = array(
            'summary' => 'Google I/O 2015',
            'location' => '800 Howard St., San Francisco, CA 94103',
            'description' => 'A chance to hear more about Google\'s developer products.',
            'start' => array(
                'dateTime' => '2015-05-28T09:00:00-07:00',
                'timeZone' => 'America/Los_Angeles',
            ),
            'end' => array(
                'dateTime' => '2015-05-28T17:00:00-07:00',
                'timeZone' => 'America/Los_Angeles',
            ),
            'recurrence' => array(
                'RRULE:FREQ=DAILY;COUNT=2'
            ),
            'attendees' => array(
                array('email' => 'lpage@example.com'),
                array('email' => 'sbrin@example.com'),
            ),
            'reminders' => array(
                'useDefault' => FALSE,
                'overrides' => array(
                    array('method' => 'email', 'minutes' => 24 * 60),
                    array('method' => 'popup', 'minutes' => 10),
                ),
            ),
        );

	$this->googlecalendar->insert($data);
```

- You can also set some option parameters:
```php
$this->googlecalendar->singleEvents(true)->showDeleted(false)->showHiddenInvitations(false)
            ->orderBy("startTime")->timeMin(mktime($hour, $minute, $second, $month, $day, $year))
            ->timeMax(mktime($hour, $minute, $second, $month, $day, $year))
            ->updatedMin(mktime($hour, $minute, $second, $month, $day, $year))
            ->maxResults(10)
            ->getAll();
```


------------

Some tips:

This command will bring all the gogle services in your vendor folder and creates a lot of unnecessary files.
**composer require google/apiclient:^2.11**

So if you only need some specific services and don't need files for other services like addmob,drive,youtube you can put this in composer and run composer update

```json
"require": {
		"php": ">=5.3.7",
		"google/apiclient": "2.11"
	},
"scripts": {
		"post-update-cmd": "Google\\Task\\Composer::cleanup"
	},
	"extra": {
		"google/apiclient-services": [
			"Calendar"
		]
	},
```

and run 
    composer update

This will remove unnecessary service's files

For more details please checkout [Google Clanader Php Docs ](https://developers.google.com/calendar/api/v3/reference/events "Google Clanader Php Docs ")
