# Indonesian Agency for Meteorology, Climatology and Geophysics (BMKG) Weather API

> This API is experimental!
**`Do not use for commercial purpose!`**

This API use PHP to serve HTTP request and reply response in JSON. However, we have a database to store areas data but not meant to save weather forecast value(s).

## Syntax

> `https://api-bmkg.herokuapp.com`/api/core/forecast.php?id=**[areaid \*]**&name=**[displayname]**&unit=**[temperature_unit ^C/F]**&masa=**[certain time ^ 1,2,3,4 delimited by commas]**

### Note:
>
> `host` -> this might be changed during development period without making change in this README file.
>
> \* required
>
> ^ default
>
> Certain time available are (pagi/morning = 1, siang/afternoon = 2, malam/night = 3, dini hari/dawn = 4). The order is not important, but make sure to delimit them with commas. This parameter is optional, default is 1,2,3,4.
