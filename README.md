Mr. Base Config
=========

## What does it do?

EXT:mr_base_config helps you to organize your TypoScript and TSConfig in your Template-Extension.

## How do I install it?

1. Basically download and install the extension.

2. Create a ext_configuration.php in your Template-Extension and add the config like:
```php
// ext_configuration.php
// this will add TypoScript and TSconfig to all "site roots"
return [
	'Typoscript' => [
		'_DEFAULT' => [
		    'Typoscripts' => [
		        'EXT:news/Configuration/TypoScript',
		        'EXT:css_styled_content/static',
		     ],
			'Extensions' => [
				'news'
			],
        ]
	],
	Tsconfig => [
	    '_DEFAULT' => [
            'Configuration/TsConfig/Page/Rte.ts',
            'Configuration/TsConfig/Page/Config.ts',
            'Configuration/TsConfig/User/Config.ts'
        ],
	]
];
```

```php
// ext_configuration.php
// this will add TypoScript to the "site root" with the sys_domain "your-domain.de" and TSconfig to the page "5"
return [
	'Typoscript' => [
		'your-domain.de' => [
		    'Typoscripts' => [
		        'EXT:news/Configuration/TypoScript',
		        'EXT:css_styled_content/static',
		     ],
			'Extensions' => [
				'news'
			],
        ]
	],
	Tsconfig => [
	    '5' => [
            'Configuration/TsConfig/Page/Rte.ts',
            'Configuration/TsConfig/Page/Config.ts',
            'Configuration/TsConfig/User/Config.ts'
        ],
	]
];
```

3. To load the Configfile
```php
// ext_localconf.php
\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Scarbous\MrBaseConfig\Service\TemplatConfigService', $_EXTKEY);
```

4. Define your "site root" and add one empty TypoScript Template to the "site root".
This both define the place where the TypoScript will be added.

### Based on

That the system can do the job your Template Extension needs the following folder structure:

* template_extension
  *  Configuration
    * TypoScript
      * setup.txt
      * constants.txt
  * Extensions
    * news
      * Configuration
        * TypoScript
          * setup.txt
          * constants.txt

## Why use it?

It helps you to organise your Template-Extension a bit more.
