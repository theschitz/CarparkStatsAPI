# CarparkStatsAPI
API for reading statistics from parkeringjonkoping.se

## Using the API

```
GET api/?<params>
```

### Parameters

|Parameter      |Type       |Description|
|---------------|-----------|-----------|
|limit          |integer    |Limits the number of rows returned.|
|fromDatetime   |datetime   |Returns result where *datetime* > fromDatetime.|
|toDatetime     |datetime   |Returns result where *datetime* < toDatetime.|
|name           |string     |Returns result where *name* = name. See [Parking Areas](#parkingareas).|
|orderby        |string     |Orders descending by given column name of either *name* or *occupancy*.|
|               |           |                                                                       |


## Database

Description of table.

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

## <a name="parkingareas"></a>Parking Areas
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
