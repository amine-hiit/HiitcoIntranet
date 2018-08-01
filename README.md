conge
=====

A Symfony project created on March 31, 2018, 9:54 am.


RestApi
===
register user
-------------
```json
{
    "username": "string",
    "email": "string@string.st",
    "plainPassword":{
        "first":"xxx",
        "second":"xxx"
    },
    "civility": "mr",
    "maritalStatus": "married",
    "cnssNumber": 100,
    "startDate": "mm/dd/yyyy",
    "currentPosition": "string",
    "status": "CDD"
}
```

new formation
-------------
```json
{  
    "startDate": "mm/dd/yyyy",
    "endDate": "mm/dd/yyyy",
    "organization": "string",
    "country": "string",
    "diploma": "string",
    "level": integer,
    "speciality": "string"
  
}
```

new language
-------------
```json
  {
    "id": 0,
    "language": id,
    "level": "string"
  }
```

request vacation
-------------
```json
  {
    "type": "string",
    "reason": "string",
    "startDate": "mm/dd/yyyy",
    "endDate": "mm/dd/yyyy",
    "dayPeriod": "string"
  }
```

validate vacation
-------------
```json
  {
    "validation": "accepter",
    "refuseReason": "string"
  }
```

