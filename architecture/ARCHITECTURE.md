# Student Database System Architecture

## Modularity

This app (student database system), is made of many modular components which i have made whilst creating this app (will be added to my collection of reusable code later)

## Abstraction

Most of the system is similar to each other, so instead of repeating myself every time (which is less flexible), i build abstractions via new architecture, classes, or functions. Although its more of just classes since _this is written using the OOP paradigm_ in favor of composer's autoloader (which will automatically require my classes without me having to manually require them in each file). This setup also allows me to develop features _without_ affecting much of the other features.

Although most of the architecture is _imperative_, some are _declarative_ like the **_model system_**, the **_model system_** allows for describing the database's data and manifesting them to real Object Oriented PHP Objects. This also introduces the Object Oriented Procedural Programming of the Repositories, which are constructs that manipulate and manifests database data. Repositories manifest data in forms of the declared Models. Each model can map a _database column_ into a _field_ (which is accessed via Reflection).

In contrasts other architecture in this app, the Templating Architecture (formerly known as Layouting architecture, and no, this is actually just functions that operate on strings.) allows the use of reusable markup templates (i do not have a compiler, so yeah, a bunch of class and function calls).
