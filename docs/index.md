---
layout: docs
title: Welcome
next_section: installation
permalink: /docs/home/
---

Many time in PHP and all the programming languages there is a moment when the code gets too large, there are too many classes and each class has different
dependencies, configuration and variations in the way the objects are instantiated, manipulate this code for a simple change turns into a fairly complex task.

Here is where dependency injection comes to solve many of this issues when code is tight and very coupled.

## Inversion of Control

[Dependency injection](http://en.wikipedia.org/wiki/Dependency_injection) is a software design pattern, part of the [inversion of control](http://en.wikipedia.org/wiki/Inversion_of_control#Implementation_techniques) technique, made popular by [Martin Fowler](http://en.wikipedia.org/wiki/Martin_Fowler). The principle of this pattern is the separation of dependencies from behaviors in object oriented software architectures.

## So what is [Cradlecore IOC](https://github.com/asotog88/cradlecore-ioc) ?

Is a php library that aims help the developers to implement dependency injection on PHP. The library provides to mechanisms for dependency injection:
<ul>
    <li>By class constructor</li>
    <li>By setter/getter class method's approach</li>
</ul>

Implementation of each mechanism of injection will depend on the existent classes, but both of them can be easily configured through the xml configurations that this library uses to build the container of objects.