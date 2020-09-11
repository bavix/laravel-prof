![Screenshot from 2020-07-12 11-56-41](https://user-images.githubusercontent.com/5111255/87242600-e5266c00-c436-11ea-8c99-da9181d929ad.png)

# Laravel Prof

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/bavix/laravel-prof/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/bavix/laravel-prof/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/bavix/laravel-prof/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/bavix/laravel-prof/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/bavix/laravel-prof/badges/build.png?b=master)](https://scrutinizer-ci.com/g/bavix/laravel-prof/build-status/master)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/bavix/laravel-prof/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)

[![Package Rank](https://phppackages.org/p/bavix/laravel-prof/badge/rank.svg)](https://packagist.org/packages/bavix/laravel-prof)
[![Latest Stable Version](https://poser.pugx.org/bavix/laravel-prof/v/stable)](https://packagist.org/packages/bavix/laravel-prof)
[![Latest Unstable Version](https://poser.pugx.org/bavix/laravel-prof/v/unstable)](https://packagist.org/packages/bavix/laravel-prof)
[![License](https://poser.pugx.org/bavix/laravel-prof/license)](https://packagist.org/packages/bavix/laravel-prof)
[![composer.lock](https://poser.pugx.org/bavix/laravel-prof/composerlock)](https://packagist.org/packages/bavix/laravel-prof)

Laravel Prof - Code profiling made easy in production. 
Mark code snippets using services and see profiling results in `grafana`, `redash` 
and other analytical systems.

* **Vendor**: bavix
* **Package**: Laravel Prof
* **Version**: [![Latest Stable Version](https://poser.pugx.org/bavix/laravel-prof/v/stable)](https://packagist.org/packages/bavix/laravel-prof)
* **Laravel Version**: `6.x`, `7.x`, `8.x`
* **PHP Version**: 7.2+ 
* **[Composer](https://getcomposer.org/):** `composer require bavix/laravel-prof`

### Usage
Add `profile_logs` table to clickhouse...

```sql
create table profile_logs
(
    hostname   String,
    project    String,
    version    String,
    userId     Nullable(String),
    sessionId  Nullable(String),
    requestId  String,
    requestIp  String,
    eventName  String,
    target     String,
    latency    Float32,
    memoryPeak Int32,
    date       Date,
    created    DateTime
)
    engine = MergeTree(date, (date, project, eventName), 8192);
```

Run the consumer
```bash
./artisan queue:work
```

Set up grafana / redash and enjoy.

![Screenshot from 2020-07-11 14-45-55](https://user-images.githubusercontent.com/5111255/87223389-41c94e80-c385-11ea-9ce0-a36643f5fb5c.png)

---
Supported by

[![Supported by JetBrains](https://cdn.rawgit.com/bavix/development-through/46475b4b/jetbrains.svg)](https://www.jetbrains.com/)
