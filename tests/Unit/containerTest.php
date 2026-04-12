<?php

test('it can resolve something out of the container', function () {
 $container=new container();
 $container->bind('foo', fn () => 'bar' );
 $result=$container->resolve('foo');
 expect($result)->toEqual('bar');
});
