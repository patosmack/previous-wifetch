ABOUT

This implements the Plug n Pay API, as documented on the Plug n Pay website.
The example PHP code available there implements only one method - 'auth' -
and you're left to your own devices to figure out the rest of it. While
that's perfectly fine, it means that there's a lot of people rewriting
exactly the same code out there, and this seems like an incredible waste
of time.

To paraphrase the PnP tech support guy I spoke with, any PHP programmer
who knows what he's doing can take the API documentation and feed
arguments to Curl to make stuff work.

But ... why would I want to? Code reuse is the point of APIs, and having
a thousand developers reimplenet the same batch of code is profoundly
pointless.

So, here you go.

This code is released under the terms of the ASL2 (Apache Software
License, version 2).

Hosted at http://code.google.com/p/plugnpay-php
Sign up and join the conversation.

INSTALLATION

Put PnP.php somewhere convenient and ...

require_once( 'path/to/PnP.php' );
$p = new PnP();
$p->auth(
    array(
        'card-number' => '4111111111111111',
        'card-name'   => 'cardtest',
        'card-amount' => '100.23',
        'card-exp'    => '11/09',
        'ship-name'   => 'cardtest',
        'card-cvv'      => '123',
    )
);
print_r( $p->query_trans );

See the complete documentation, with code examples, in docs/index.html

See also the tests in the tests/ directory for more examples.

