Why yet another Framework?

> There are already so many great frameworks in different languages; why is there a need for another one?

With that in mind, I'd like to elaborate on the reasons why I started this project and why I think it's worth the effort. These are all personal opinions and experiences, so take them with a grain of salt.

+ Starting off with Laravel: it’s a great framework, especially the Blade components with slots. They allow you to build complex, nested components however you wish. But the problem is that it requires a dedicated template engine called Blade. I don’t want to learn another template language because PHP shortcodes already provide everything I need. It also requires me to install another Laravel Blade extension in my IDE, which ended up messing things up more than it helped. So I ended up mixing PHP in Blade files with some Blade syntax, which isn’t how it’s supposed to be used.  
+ Laravel’s components are heavily based on naming conventions. You need to know where to put files and how to name them. If you want to add logic, you have to create a component file somewhere and a view file somewhere else, and you can also prefix them. Its the opposite of how Components work in JavaScript world.
+ Laravel’s use of magic functions, dependency injection, and facades can be confusing. Which one should I use, and when? Whenever possible, I used DI, but I had no idea which class name should be injected.
+ Setting up a Laravel project creates so many files and folders, even if I don’t need them. I’m never sure what can be deleted and what can’t. It should have as few files and as little configuration as possible. Maybe I don’t need sessions or databases, but they’re all enabled (and required?) by default in Laravel, and it’s hard to disable them.  
+ Yii2 was a great framework with a straightforward approach, but it was missing good DI support. Instead, it used the service locator approach, which prevents autocomplete in IDEs.  
+ Nuxt.js is a great framework that starts with a single file and grows with your project. Also, Vue’s single-file component approach is great—having everything in one place just makes sense. But the constant switching between server side and client side made me nervous.  
+ If you come from a PHP Composer world, node modules (npm, yarn, pnpm, bun) are a pain. Something will always break, and I’m not sure having more package managers makes things better.  
+ Astro’s routing system, paired with the single-component approach, is great. It’s (if defined) always server-side rendering, which is almost perfect. It gets so many things right, but there’s TypeScript and ESM/UMD, which sooner or later complicates things (you will find a solution, but it’s not the way it should be). Also, Astro is missing an approach to externalize controller logic (for example, rendering a component within the controller logic of another component and modifying its output).

These experiences made me wonder if:

+ It’s possible to have a single-file approach with PHP  
+ A route-based framework with PHP  
+ Components with slots in PHP  
+ Everything is IDE-friendly while typing  

It is possible. That’s why Darken PHP was born.