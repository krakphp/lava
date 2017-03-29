<?php


describe('->addStack', function() {
    it('adds a stack into the application', function() {
        $this->app->addStack('test');
        assert($this->app->has('stacks.test'));
    });
});
describe('->hasStack', function() {
    it('checks if a stack exists in the app', function() {
        $this->app->addStack('test');
        assert(!$this->app->hasStack('bad') && $this->app->hasStack('test'));
    });
});
describe('->stack', function() {
    it('returns the given stack by name', function() {
        $this->app->addStack('test');
        assert($this->app->stack('test') instanceof Krak\Mw\Stack);
    });
    it('throws an exception if the stack does not exist', function() {
        try {
            $this->app->stack('bad');
            assert(false);
        } catch (\Exception $e) {
            assert(true);
        }
    });
});

foreach (['http', 'routes', 'invokeAction', 'marshalResponse', 'renderError'] as $stack_name) {
    describe("->${stack_name}Stack", function() use ($stack_name) {
        it("returns the $stack_name stack", function() use ($stack_name) {
            assert($this->app->{$stack_name . 'Stack'}() instanceof Krak\Mw\Stack);
        });
    });
}
