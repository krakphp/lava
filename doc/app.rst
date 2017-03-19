===
App
===

The app is the central point for an http application. It manages core services to build
your application: Cargo Container, Event Emitter, Stacks of middleware,
and routes.

The app is just an interface into each of those separate components and also provides the glue
to serve applications. The packages themselves have the ability to define how the app will function.
This is done by registering services into the app as they would with pimple, and then modifying those services.
