conge
=====

A Symfony project created on March 31, 2018, 9:54 am.


RestApi
===
register employee
---
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

validate employee
-----------------
```json
{
    "firstName": "string",
    "lastName": "string",
    "birthday": "mm/dd/yyyy",
    "dependentChild": 0,
    "phoneNumber": "0666666666",
    "address": "string",
    "formations": [
		{
		    "startDate": "mm/dd/yyyy",
		    "endDate": "mm/dd/yyyy",
		    "organization": "string",
		    "country": "string",
		    "diploma": "string",
		    "level": 1,
		    "speciality": "string"
		}
    ],
    "employee_languages": [
        {
            "language": 1,
            "level": "beginner"
        },
        {
            "language": 3,
            "level": "advanced"
        }
    ],
    "experiences": [
  		{
            "position": "string",
            "description": "string",
            "startDate": "mm/dd/yyyy",
            "endDate": "mm/dd/yyyy",
            "city": "string",
            "country": "string",
            "employer": "string"
	    }
    ],
    "projects": [
        {
            "name": "string",
            "date": "mm/dd/yyyy",
            "description": "string"
        }
    ]
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




|   id | language         |
| --- | ---         |
|   1 | Abkhazian         |
|   2 | Afar              |
|   3 | Afrikaans         |
|   4 | Akan              |
|   5 | Albanian          |
|   6 | Amharic           |
|   7 | Arabic            |
|   8 | Aragonese         |
|   9 | Armenian          |
|  10 | Assamese          |
|  11 | Avaric            |
|  12 | Avestan           |
|  13 | Aymara            |
|  14 | Azerbaijani       |
|  15 | Bambara           |
|  16 | Bashkir           |
|  17 | Basque            |
|  18 | Belarusian        |
|  19 | Bihari            |
|  20 | Bislama           |
|  21 | Bosnian           |
|  22 | Breton            |
|  23 | Bulgarian         |
|  24 | Burmese           |
|  25 | Catalan           |
|  26 | Chamorro          |
|  27 | Chechen           |
|  28 | Chinese           |
|  29 | Chinese           |
|  30 | Chuvash           |
|  31 | Cornish           |
|  32 | Corsican          |
|  33 | Cree              |
|  34 | Croatian          |
|  35 | Czech             |
|  36 | Danish            |
|  37 | Divehi            |
|  38 | Dutch             |
|  39 | Dzongkha          |
|  40 | English           |
|  41 | Esperanto         |
|  42 | Estonian          |
|  43 | Ewe               |
|  44 | Faroese           |
|  45 | Fijian            |
|  46 | Finnish           |
|  47 | French            |
|  48 | Fula              |
|  49 | Galician          |
|  50 | Gaelic            |
|  51 | Georgian          |
|  52 | German            |
|  53 | Greek             |
|  54 | Greenlandic       |
|  55 | Guarani           |
|  56 | Gujarati          |
|  57 | Haitian           |
|  58 | Hausa             |
|  59 | Hebrew            |
|  60 | Herero            |
|  61 | Hindi             |
|  62 | Hiri              |
|  63 | Hungarian         |
|  64 | Icelandic         |
|  65 | Ido               |
|  66 | Igbo              |
|  67 | Indonesian        |
|  68 | Interlingua       |
|  69 | Interlingue       |
|  70 | Inuktitut         |
|  71 | Inupiak           |
|  72 | Irish             |
|  73 | Italian           |
|  74 | Japanese          |
|  75 | Javanese          |
|  76 | Kalaallisut       |
|  77 | Kannada           |
|  78 | Kanuri            |
|  79 | Kashmiri          |
|  80 | Kazakh            |
|  81 | Khmer             |
|  82 | Kikuyu            |
|  83 | Kinyarwanda       |
|  84 | Kirundi           |
|  85 | Kyrgyz            |
|  86 | Komi              |
|  87 | Kongo             |
|  88 | Korean            |
|  89 | Kurdish           |
|  90 | Kwanyama          |
|  91 | Lao               |
|  92 | Latin             |
|  93 | Latvian           |
|  94 | LimburgishLingala |
|  95 | Lithuanian        |
|  96 | Luga-Katanga      |
|  97 | Luganda           |
|  98 | Luxembourgish     |
|  99 | Manx              |
| 100 | Macedonian        |
| 101 | Malagasy          |
| 102 | Malay             |
| 103 | Malayalam         |
| 104 | Maltese           |
| 105 | Maori             |
| 106 | Marathi           |
| 107 | Marshallese       |
| 108 | Moldavian         |
| 109 | Mongolian         |
| 110 | Nauru             |
| 111 | Navajo            |
| 112 | Ndonga            |
| 113 | Northern          |
| 114 | Nepali            |
| 115 | Norwegian         |
| 116 | Norwegian         |
| 117 | Norwegian         |
| 118 | Nuosu             |
| 119 | Occitan           |
| 120 | Ojibwe            |
| 121 | Old               |
| 122 | Oriya             |
| 123 | Oromo             |
| 124 | Ossetian          |
| 125 | Pāli              |
| 126 | Pashto            |
| 127 | PersianPolish     |
| 128 | Portuguese        |
| 129 | PunjabiQuechua    |
| 130 | Romansh           |
| 131 | Romanian          |
| 132 | Russian           |
| 133 | Sami              |
| 134 | Samoan            |
| 135 | Sango             |
| 136 | Sanskrit          |
| 137 | Serbian           |
| 138 | Serbo-Croatian    |
| 139 | Sesotho           |
| 140 | Setswana          |
| 141 | Shona             |
| 142 | Sichuan           |
| 143 | Sindhi            |
| 144 | Sinhalese         |
| 145 | Siswati           |
| 146 | Slovak            |
| 147 | Slovenian         |
| 148 | Somali            |
| 149 | Southern          |
| 150 | Spanish           |
| 151 | Sundanese         |
| 152 | SwahiliSwati      |
| 153 | Swedish           |
| 154 | Tagalog           |
| 155 | Tahitian          |
| 156 | Tajik             |
| 157 | Tamil             |
| 158 | Tatar             |
| 159 | Telugu            |
| 160 | Thai              |
| 161 | Tibetan           |
| 162 | Tigrinya          |
| 163 | Tonga             |
| 164 | Tsonga            |
| 165 | Turkish           |
| 166 | Turkmen           |
| 167 | Twi               |
| 168 | Uyghur            |
| 169 | Ukrainian         |
| 170 | Urdu              |
| 171 | Uzbek             |
| 172 | Venda             |
| 173 | Vietnamese        |
| 174 | Volapük           |
| 175 | Wallon            |
| 176 | Welsh             |
| 177 | Wolof             |
| 178 | Western           |
| 179 | Xhosa             |
| 180 | Yiddish           |
| 181 | Yoruba            |
| 182 | Zhuang            |
| 183 | Zulu              |
