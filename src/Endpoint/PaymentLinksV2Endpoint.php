<?php

namespace Ipag\Sdk\Endpoint;

use Ipag\Sdk\Core\Endpoint;
use Ipag\Sdk\Http\Response;
use Ipag\Sdk\Model\PaymentLinkV2\PaymentLinkV2;

class PaymentLinksV2Endpoint extends Endpoint
{
    protected string $location = '/service/v2/payment_links';

    /**
     * Endpoint para criar um recurso `Payment Link V2`
     *
     * @param PaymentLinkV2 $paymentLink
     * @return Response
     */
    public function create(PaymentLinkV2 $paymentLink): Response
    {
        return $this->_POST($paymentLink->jsonSerialize());
    }

    /**
     * Endpoint para atualizar um recurso `Payment Link V2`
     *
     * @param PaymentLinkV2 $paymentLink
     * @param integer $id
     * @return Response
     *
     * @codeCoverageIgnore
     */
    public function update(PaymentLinkV2 $paymentLink, int $id): Response
    {
        return $this->_PUT($paymentLink->jsonSerialize(), ['id' => $id]);
    }

    /**
     * Endpoint para obter um recurso `Payment Link V2`
     *
     * @param integer $id
     * @return Response
     *
     * @codeCoverageIgnore
     */
    public function get(int $id): Response
    {
        return $this->_GET(['id' => $id]);
    }

    /**
     * Endpoint para listar recursos `Payment Link V2`
     *
     * @param array|null $filters
     * @return Response
     *
     * @codeCoverageIgnore
     */
    public function list(?array $filters = []): Response
    {
        return $this->_GET($filters ?? []);
    }

    /**
     * Endpoint para listar as transações de um recurso `Payment Link V2`
     *
     * @param integer $id
     * @param array|null $filters
     * @return Response
     *
     * @codeCoverageIgnore
     */
    public function listTransactions(int $id, ?array $filters = []): Response
    {
        return $this->_GET($filters ?? [], [], "/{$id}/transactions");
    }
}
