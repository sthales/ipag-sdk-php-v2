<?php

namespace Ipag\Sdk\Endpoint;

use Ipag\Sdk\Core\Endpoint;
use Ipag\Sdk\Http\Response;
use Ipag\Sdk\Model\SubscriptionV2\SubscriptionV2;

class SubscriptionV2Endpoint extends Endpoint
{
    protected string $location = '/service/v2/subscriptions';

    /**
     * Endpoint para criar um recurso `Subscription`
     *
     * @param SubscriptionV2 $subscription
     * @return Response
     */
    public function create(SubscriptionV2 $subscription): Response
    {
        return $this->_POST($subscription->jsonSerialize());
    }

    /**
     * Endpoint para atualizar um recurso `Subscription`
     *
     * @param SubscriptionV2 $subscription
     * @param integer $id
     * @return Response
     *
     * @codeCoverageIgnore
     */
    public function update(SubscriptionV2 $subscription, int $id): Response
    {
        return $this->_PUT($subscription->jsonSerialize(), ['id' => $id]);
    }
}
