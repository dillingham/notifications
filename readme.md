# Notifications


<p>
    <a href="https://github.com/jetstreamkit/notifications/actions">
        <img src="https://github.com/jetstreamkit/notifications/workflows/tests/badge.svg" alt="Build Status">
    </a>
    <a href="https://packagist.org/packages/jetstreamkit/notifications">
        <img src="https://img.shields.io/packagist/v/jetstreamkit/notifications" alt="Latest Stable Version">
    </a>
    <a href="https://packagist.org/packages/jetstreamkit/notifications">
        <img src="https://img.shields.io/packagist/dt/jetstreamkit/notifications" alt="Total Downloads">
    </a>
    <a href="https://twitter.com/im_brian_d">
        <img src="https://img.shields.io/twitter/follow/im_brian_d?color=%231da1f1&label=Twitter&logo=%231da1f1&logoColor=%231da1f1&style=flat-square" alt="twitter">
    </a>
</p>


Notifications for Laravel Jetstream / Inertia

---


### Install
Add the composer package:
```
composer require jetstreamkit/notifications
```

And the [jetstreamkit](https://github.com/jetstreamkit/jetstreamkit) npm package

```
npm install @jetstreamkit/jetstreamkit
```

Call the Laravel command to add a migration.
```
php artisan notifications:table
```
Add the notifiable trait to the user model:
```php
use Notifiable;
```
Import the component into `AppLayout.vue`
```js
import { Notifications } from '@jetstreamkit/jetstreamkit';
```

For the full Laravel docs for notifications: [read more](https://laravel.com/docs/notifications)

### Available Props
Here are options to help define the component
```html
<notifications list="bg-red-500">
    <!-- example of props   -->
</notifications>
```

| Option | Description |
| ------ | ----------- |
| list   | tailwind clases to apply to list |
| item   | tailwind clases to apply to item |
| to     | route name to link each item |
| header     | boolean if to show header |
| footer     | boolean if to show footer |

### Available Slots


The list item

```html
<notifications>
    <template #item="{ notification }">
        {{ notification.id }}
        {{ notification.type }}
        {{ notification.data }}
    </template>
</notifications>
```

Above the list

```html
<notifications>
    <template #above>
        <!-- a custom header -->
    </template>
</notifications>
```

Below the list

```html
<notifications>
    <template #below>
        <!-- a custom footer-->
    </template>
</notifications>
```

The dropdown icon

```html
<notifications>
    <template #trigger>
        <!-- replace the icon -->
    </template>
</notifications>
```

The empty state

```html
<notifications>
    <template #empty>
        <p>All caught up.</p>
    </template>
</notifications>
```

### Available Endpoints

| Method | Endpoint | Action |
| ------ | -------- | ------ |
| get | /notifications | all |
| get | /notifications/unread | unread |
| get | /notifications/read | read |
| get | /notifications/count | count |
| post | /notifications/clear | clear |
| post | /notifications/{notification}/mark-as-read | markAsRead |
| delete | /notifications/{notification} | destroy |
