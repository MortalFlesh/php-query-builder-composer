Query Builder Composer
======================

[![Latest Stable Version](https://img.shields.io/packagist/v/mf/query-builder-composer.svg)](https://packagist.org/packages/mf/query-builder-composer)
[![Build Status](https://travis-ci.org/MortalFlesh/php-query-builder-composer.svg?branch=master)](https://travis-ci.org/MortalFlesh/php-query-builder-composer)
[![Coverage Status](https://coveralls.io/repos/github/MortalFlesh/php-query-builder-composer/badge.svg?branch=master)](https://coveralls.io/github/MortalFlesh/php-query-builder-composer?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/mf/query-builder-composer.svg)](https://packagist.org/packages/mf/query-builder-composer)
[![License](https://img.shields.io/packagist/l/mf/query-builder-composer.svg)](https://packagist.org/packages/mf/query-builder-composer)

**QueryBuilderComposer** for easier composing `Doctrine\\ORM\\QueryBuilder` parts

## Install
```bash
    composer require mf/query-builder-composer
```

## Usage 

### Why? What is a problem?
If you have complex methods for building `Query` via `QueryBuilder`, you might be in same situation as I am.
I have many similar methods to build different `Queries` and I cant see a clear way how to reuse my `QueryBuilder` parts.

So I decided to create this `QueryBuilderComposer` to make this issue easier.

### Example of complex methods with duplicated parts
_Methods are simplified so they might not be 100% correct._

```php
public function countFreeApproved()
{
    return $this->createQueryBuilder('c')
        ->select('COUNT(c.id)')
        ->where('c.price = 0')
        ->andWhere('c.approved = TRUE')
        ->getQuery()
        ->getSingleScalarResult();
}

public function findMostViewedFreeCourses()
{
    return $this->createQueryBuilder('c')
        ->select('c, i, COUNT(views) AS HIDDEN views')
        ->innerJoin('c.image', 'i')
        ->where('c.approved = TRUE')
        ->andWhere('c.price = 0')
        ->orderBy('views', 'DESC')
        ->addOrderBy('c.position', 'ASC')
        ->getQuery()
        ->getResult();
}

public function findFreeCourses()
{
    return $this->createQueryBuilder('c')
        ->select('c, i')
        ->innerJoin('c.image', 'i')
        ->where('c.approved = TRUE')
        ->andWhere('c.price = 0')
        ->addOrderBy('c.position', 'ASC')
        ->getQuery()
        ->getResult();
}
```

Now you can have some idea of those parts which are same for more cases and they can be composed and defined once!

### Composition of parts

#### Step 1 (rewrite to `QueryBuilderComposer`)
```php
public function countFreeApproved()
{
    return $queryBuilderComposer
        ->compose(
            $this->createQueryBuilder('c'),
            [
                ['select', 'COUNT(c.id)'],
                ['where', 'c.price = 0'],
                ['andWhere', 'c.approved = TRUE'],
            ]
        )
        ->getQuery()
        ->getResult();
}
    
public function findMostViewedFreeCourses()
{
    return $queryBuilderComposer
        ->compose(
            $this->createQueryBuilder('c'),
            [
                ['select', 'c, i, COUNT(views) AS HIDDEN views'],
                ['innerJoin', 'c.image', 'i'],
                ['where', 'c.approved = TRUE'],
                ['andWhere', 'c.price = 0'],
                ['orderBy', 'views', 'DESC'],
                ['addOrderBy', 'c.position', 'ASC'],
            ]
        )
        ->getQuery()
        ->getResult();
}
    
public function findFreeCourses()
{
    return $queryBuilderComposer
        ->compose(
            $this->createQueryBuilder('c'),
            [
                ['select', 'c, i'],
                ['innerJoin', 'c.image', 'i'],
                ['where', 'c.approved = TRUE'],
                ['andWhere', 'c.price = 0'],
                ['addOrderBy', 'c.position', 'ASC'],
            ]
        )
        ->getQuery()
        ->getResult();
}
```

#### Step 2 (store common rules to class constants to allow easier reuse)
```php
const SELECT_COURSE = ['select', 'c, i'];
const JOIN_IMAGE = ['innerJoin', 'c.image', 'i'];
const FREE_COURSES = ['andWhere', 'c.price = 0'];
const APPROVED_ONLY = ['andWhere', 'c.approved = TRUE'];
const DEFAULT_ORDER = ['addOrderBy', 'c.position', 'ASC'];
    
public function countFreeApproved()
{
    return $this->queryBuilderComposer
        ->compose(
            $this->createQueryBuilder('c'),
            [
                ['select', 'COUNT(c.id)'],
                self::FREE_COURSES,
                self::APPROVED_ONLY,
            ]
        )
        ->getQuery()
        ->getResult();
}
    
public function findMostViewedFreeCourses()
{
    return $this->queryBuilderComposer
        ->compose(
            $this->createQueryBuilder('c'),
            [
                self::SELECT_COURSE, 
                ['COUNT(views) AS HIDDEN views'],
                self::JOIN_IMAGE,
                self::FREE_COURSES,
                self::APPROVED_ONLY,
                ['orderBy', 'views', 'DESC'],
                self::DEFAULT_ORDER,
            ]
        )
        ->getQuery()
        ->getResult();
}
    
public function findFreeCourses()
{
    return $this->queryBuilderComposer
        ->compose(
            $this->createQueryBuilder('c'),
            [
                self::SELECT_COURSE,
                self::JOIN_IMAGE,
                self::FREE_COURSES,
                self::APPROVED_ONLY,
                self::DEFAULT_ORDER,
            ]
        )
        ->getQuery()
        ->getResult();
}
```

#### Step 3 (compose parts)
```php
const SELECT_COURSE = ['select', 'c, i'];
const JOIN_IMAGE = ['innerJoin', 'c.image', 'i'];
const FREE_COURSES = ['andWhere', 'c.price = 0'];
const APPROVED_ONLY = ['andWhere', 'c.approved = TRUE'];
const DEFAULT_ORDER = ['addOrderBy', 'c.position', 'ASC'];
    
const SELECT_COURSE_W_IMAGE = [
    self::SELECT_COURSE,
    self::JOIN_IMAGE,
];
    
const FREE_APPROVED = [
    self::FREE_COURSES,
    self::APPROVED_ONLY,
];
    
public function countFreeApproved()
{
    return $this->queryBuilderComposer
        ->compose(
            $this->createQueryBuilder('c'),
            array_merge(
                [['select', 'COUNT(c.id)']],
                self::FREE_APPROVED
            )
        )
        ->getQuery()
        ->getResult();
}
    
public function findMostViewedFreeCourses()
{
    return $this->queryBuilderComposer
        ->compose(
            $this->createQueryBuilder('c'),
            array_merge(
                self::SELECT_COURSE_W_IMAGE,
                [
                    ['COUNT(views) AS HIDDEN views'],
                    ['orderBy', 'views', 'DESC'],
                    self::DEFAULT_ORDER,
                ],
                self::FREE_APPROVED
            )
        )
        ->getQuery()
        ->getResult();
}
    
public function findFreeCourses()
{
    return $this->queryBuilderComposer
        ->compose(
            $this->createQueryBuilder('c'),
            array_merge(
                self::SELECT_COURSE_W_IMAGE,
                [self::DEFAULT_ORDER],
                self::FREE_APPROVED
            )
        )
        ->getQuery()
        ->getResult();
}
```

#### Step 4 (use _syntax sugar_ over `array_merge`)
```php
public function countFreeApproved()
{
    return $this->queryBuilderComposer
        ->mergeCompose(
            $this->createQueryBuilder('c'),
            [['select', 'COUNT(c.id)']],
            self::FREE_APPROVED
        )
        ->getQuery()
        ->getResult();
}
    
public function findMostViewedFreeCourses()
{
    return $this->queryBuilderComposer
        ->mergeCompose(
            $this->createQueryBuilder('c'),
            self::SELECT_COURSE_W_IMAGE,
            [
                ['COUNT(views) AS HIDDEN views'],
                ['orderBy', 'views', 'DESC'],
                self::DEFAULT_ORDER,
            ],
            self::FREE_APPROVED
        )
        ->getQuery()
        ->getResult();
}
    
public function findFreeCourses()
{
    return $this->queryBuilderComposer
        ->mergeCompose(
            $this->createQueryBuilder('c'),
            self::SELECT_COURSE_W_IMAGE,
            [self::DEFAULT_ORDER],
            self::FREE_APPROVED
        )
        ->getQuery()
        ->getResult();
}
```

### Difference between `compose` vs `mergeCompose`
```php
$baseParts = [
    ['select', 's.id, s.name, s.age'],
    ['from', 'student', 's'],
];

$approvedMature = [
    ['andWhere', 's.approved = true'],
    ['andWhere', 's.age >= 18'],
];

// following calls are the same!
$queryBuilder = $composer->compose($this->queryBuilder, array_merge($baseParts, $approvedMature));
$queryBuilder = $composer->mergeCompose($this->queryBuilder, $baseParts, $approvedMature);
```


#### Conclusion
You can merge, compose and reuse your `QueryBuilder` parts easy.
Example above is just quick solution. You can do much more patterns over this `composition`:
- implement `Modifier` to do something with `QueryBuilder`
- implement `Closure` to be reapplied again
- ...


## How to add complex rulex to `QueryBuilder`

```php
public function complexResult()
{
    $queryBuilder = $this->createQueryBuilder('c');
    
    $queryBuilder->...  // do anything you want with QueryBuilder here
    
    return $this->queryBuilderComposer
        ->compose(
            $queryBuilder,
            [
                // add more parts here... ,
                
                function(QueryBuilder $queryBuilder) {
                    return $queryBuilder->...   // do anything you want with QueryBuilder here either
                },
                
                // add more parts here... ,
            ]
        )
        ->getQuery()
        ->getResult();
}
```

## Todo
- make `applyRule` smarter to allow following (_parse single string by spaces_):
```php
    ->compose(
        $queryBuilder,
        [
            ['select s.id s.name']
            ['from student s']
        ]
    )
```
