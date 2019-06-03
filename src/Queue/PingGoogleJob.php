<?php
/**
 * YAWIK Sitemap
 *
 * @filesource
 * @copyright 2019 CROSS Solution <https://www.cross-solution.de>
 * @license MIT
 */

declare(strict_types=1);

namespace Sitemap\Queue;

use Core\Queue\Job\MongoJob;
use Zend\Http\Client;

/**
 * TODO: description
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * TODO: write tests
 */
class PingGoogleJob extends MongoJob
{
    public function execute()
    {
        $client = new Client('http://www.google.com/ping');
        $client->setParameterGet(['sitemap' => $this->getContent()]);
        $response = $client->send();

        if (false && !$response->isOk()) {
            return $this->failure($response->getReasonPhrase());
        }

        return $this->success(sprintf(
            'Pinged: %s',
            urldecode($client->getRequest()->getUriString())
        ));
    }
}
