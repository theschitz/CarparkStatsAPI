# CarparkStatsAPI
API for reading historic data collected for [parkeringjonkoping.se](https://www.parkeringjonkoping.se) accessible through [parkeringjonkoping.se/api/](https://www.parkeringjonkoping.se/api/).

## About

The website parkeringjonkoping.se tracks available parking spaces in the town of [Jönköping](https://www.google.com/maps/place/Jönköping/), [Sweden](https://proxy.duckduckgo.com/iu/?u=https%3A%2F%2Fmedia.makeameme.org%2Fcreated%2Fsweden-come.jpg&f=1).

So you might have seen these signs at parking lots that shows you the number of free parking spaces. There are a few of these in the central parts of Jönköping. This project started out some years back when the question was raised if the data served for the signs, and also partially for the municipalitys webpage, was available through an open API. [Jönköpings Kommun](https://www.jonkoping.se/) said no and showed little interest in our call for open data. 

So I did a little digging and found the the source of the data. Not really public or open but accessible. My first idea was to create a [Twitterbot](https://twitter.com/pplatsjkpg) and then also the site [parkeringjonkoping.se](https://www.parkeringjonkoping.se). Back then there was this debate over how hard it was to find a parking space in the city (it's probably still going on) but the data often told another story. Looking at momentary data seldom shows you the big picture, so I started storing the data with the intent of sharing it through an API at some point. Well now that time has come and I share not only the data but also the source code so that the API can be easily improved.

## Disclaimer

Since I have no control over the source of the data, the collection of new data could be stopped at any time. The database and service suppling this API will however be online as long as it seems relevant. After that point I guess I'll make a backup available for download somewhere.


## Using the API

```
GET /api/?limit=5000 HTTP/1.1
Host: parkeringjonkoping.se
Accept: application/json
accept-encoding: gzip, deflate
Connection: keep-alive
cache-control: no-cache

```

See [Code samples](#codesamples)

### Query Parameters

At least one query parameter must be sent. The simplest would be 'limit'.

|Parameter      |Type       |Description|
|---------------|-----------|-----------|
|limit          |integer    |Limits the number of rows returned. Max is 5000.|
|fromDatetime   |datetime   |Returns result where *datetime* > fromDatetime.|
|toDatetime     |datetime   |Returns result where *datetime* < toDatetime.|
|name           |string     |Returns result where *name* = name. See [Parking Areas](#parkingareas).|
|orderby        |string     |Orders descending by given column name of either *name* or *occupancy*.|
|               |           |                                                                       |


## Database & Data

### Notes about the data

* Collection of new data could be stopped at any time, this is out of my control.
* The first entry was registered at 2016-09-03 17:25:59. No data is available before that point in time.
* This is raw data collected and stored. There are some gaps really weird posts in there. If something is really bothering you - file an issue and it just might be handled.
* Issues are filed here on [Github](https://github.com/theschitz/CarparkStatsAPI/issues).

### Description of table.

|Field          |Type       |Null   |Key    |Default|Extra          |
|---------------|-----------|-------|-------|-------|---------------|
|id             |int(11)    |NO     |PRI	|NULL   |auto_increment |
|datetime	    |datetime	|NO		|NULL   |	    |               |
|name	        |varchar(80)|NO		|NULL   |       |               |
|occupancy	    |int(11)	|NO		|NULL	|       |               |
|maxoccupancy	|int(11)	|NO		|NULL	|	    |               |
|marginal	    |int(11)	|NO		|NULL	|	    |               |
|hysteres	    |int(11)	|NO		|NULL	|	    |               |
|active	        |tinyint(1)	|NO		|NULL   |       |               |
|               |           |       |       |       |               |

### <a name="parkingareas"></a>Parking Areas
|Name                   |
|-----------------------|
|Spira	                |
|P-hus Biblioteket	    |
|Östra Torget	        |
|P-hus Atollen	        |
|P-garage Järnbäraren	|
|P-hus Per Brahe	    |
|P-hus Smedjan	        |
|Västra Torget	        |
|P-hus Sesam	        |

## <a name="codesamples"></a>Code Samples

### PHP

```php
<?php

$request = new HttpRequest();
$request->setUrl('http://parkeringjonkoping.se/api/');
$request->setMethod(HTTP_METH_GET);

$request->setQueryData(array(
  'limit' => '5000',
  'fromDatetime' => '2019-01-01%2000:00:00',
  'name' => 'Spira'
));

$request->setHeaders(array(
  'cache-control' => 'no-cache',
  'Connection' => 'keep-alive',
  'accept-encoding' => 'gzip, deflate',
  'Host' => 'parkeringjonkoping.se',
  'Cache-Control' => 'no-cache',
  'Accept' => 'application/json'
));

try {
  $response = $request->send();
  echo $response->getBody();
} catch (HttpException $ex) {
  echo $ex;
}
```

### Python

```Python
import requests

url = "http://parkeringjonkoping.se/api/"

querystring = { "limit":"5000",
                "fromDatetime":"2019-01-01%2000:00:00",
                "name":"Spira" }

headers = {
    'Accept': "application/json",
    'Cache-Control': "no-cache",
    'Host': "parkeringjonkoping.se",
    'accept-encoding': "gzip, deflate",
    'Connection': "keep-alive",
    'cache-control': "no-cache"
    }

response = requests.request("GET", url, headers=headers, params=querystring)

print(response.text)
```

### JavaScript

```javascript
var data = null;

var xhr = new XMLHttpRequest();
xhr.withCredentials = true;

xhr.addEventListener("readystatechange", function () {
  if (this.readyState === 4) {
    console.log(this.responseText);
  }
});

xhr.open("GET", "http://parkeringjonkoping.se/api/?limit=5000&fromDatetime=2019-01-01%2000:00:00&name=Spira");
xhr.setRequestHeader("Accept", "application/json");
xhr.setRequestHeader("Cache-Control", "no-cache");
xhr.setRequestHeader("Host", "parkeringjonkoping.se");
xhr.setRequestHeader("accept-encoding", "gzip, deflate");
xhr.setRequestHeader("Connection", "keep-alive");
xhr.setRequestHeader("cache-control", "no-cache");

xhr.send(data);
```

### cURL
    curl -X GET \
    'http://parkeringjonkoping.se/api/?limit=5000&fromDatetime=2019-01-01%2000:00:00&name=Spira' \
    -H 'Accept: application/json' \
    -H 'Cache-Control: no-cache' \
    -H 'Connection: keep-alive' \
    -H 'Host: parkeringjonkoping.se' \
    -H 'accept-encoding: gzip, deflate' \
    -H 'cache-control: no-cache'