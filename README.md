## About



Laravel 5 package that allows you to share your [Laravel localizations](https://laravel.com/docs/5.8/localization)
with your [vue](http://vuejs.org/) front-end, using [vue-i18n](https://github.com/kazupon/vue-i18n) or [vuex-i18n](https://github.com/dkfbasel/vuex-i18n).

## Install the package

In your project:
```composer require konstruktiv/vue-i18n-generator --dev```

### For Laravel 5.4 and below:
For older versions of the framework:

Register the service provider in ```config/app.php```

```php
Konstruktiv\VueI18nGenerator\GeneratorProvider::class,
```

Next, publish the package default config:

```
php artisan vendor:publish --provider="Konstruktiv\VueI18nGenerator\GeneratorProvider"
```

## Using vue-i18n

Next, you need to install one out of two supported VueJs i18n libraries. We support [vue-i18n](https://github.com/kazupon/vue-i18n) as default library. Beside that we also support [vuex-i18n](https://github.com/dkfbasel/vuex-i18n).

When you go with the default option, you only need to install the library through your favorite package manager.
### vue-i18n
```
npm i --save vue-i18n
```

```
yarn add vue-i18n
```

Then generate the include file with
```
php artisan vue-i18n:generate
```

Assuming you are using a recent version of vue-i18n (>=6.x), adjust your vue app with something like:
```js
import Vue from 'vue';
import VueInternationalization from 'vue-i18n';
import Locale from './vue-i18n-locales.generated';

Vue.use(VueInternationalization);

const lang = document.documentElement.lang.substr(0, 2);
// or however you determine your current app locale

const i18n = new VueInternationalization({
    locale: lang,
    messages: Locale
});

const app = new Vue({
    el: '#app',
    i18n,
    components: {
       ...
    }
}
```

For older vue-i18n (5.x), the initialization looks something like:
```js
import Vue from 'vue';
import VueInternationalization from 'vue-i18n';
import Locales from './vue-i18n-locales.generated.js';

Vue.use(VueInternationalization);

Vue.config.lang = 'en';

Object.keys(Locales).forEach(function (lang) {
  Vue.locale(lang, Locales[lang])
});

...
```

## Using vuex-i18n

### vuex-i18n
```
npm i --save vuex-i18n
```

```
yarn add vuex-i18n vuex
```

Next, open `config/vue-i18n-generator.php` and do the following changes:

```diff
- 'i18nLib' => 'vue-i18n',
+ 'i18nLib' => 'vuex-i18n',
```

Then generate the include file with
```
php artisan vue-i18n:generate
```

Assuming you are using a recent version of vuex-i18n, adjust your vue app with something like:
```js
import Vuex from 'vuex';
import vuexI18n from 'vuex-i18n';
import Locales from './vue-i18n-locales.generated.js';

const store = new Vuex.Store();

Vue.use(vuexI18n.plugin, store);

Vue.i18n.add('en', Locales.en);
Vue.i18n.add('de', Locales.de);

// set the start locale to use
Vue.i18n.set(Spark.locale);

require('./components/bootstrap');

var app = new Vue({
    store,
    mixins: [require('spark')]
});
```

## Generating Multiple Files

Sometimes you may want to generate multiple files as you want to make use of lazy loading. As such, you can specify that the generator produces multiple files within the destination directory.

There are two options:
1. One file per laravel module language file using switch ```--multi```
2. One file per locale using switch ```--multi-locales```

```
php artisan vue-i18n:generate --multi{-locales}
```

## Parameters

The generator adjusts the strings in order to work with vue-i18n's named formatting,
so you can reuse your Laravel translations with parameters.

resource/lang/message.php:
```php
return [
    'hello' => 'Hello :name',
];
```

in vue-i18n-locales.generated.js:
```js
...
    "hello": "Hello {name}",
...
```

Blade template:
```php
<div class="message">
    <p>{{ trans('message.hello', ['name' => 'visitor']) }}</p>
</div>
```

Vue template:
```js
<div class="message">
    <p>{{ $t('message.hello', {name: 'visitor'}) }}</p>
</div>
```
