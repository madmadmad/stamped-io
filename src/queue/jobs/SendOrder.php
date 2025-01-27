<?php
namespace verbb\stamped\queue\jobs;

use verbb\stamped\Stamped;

use craft\commerce\elements\Order;

use Craft;
use craft\queue\BaseJob;

use yii\base\Exception;

class SendOrder extends BaseJob
{
    // Properties
    // =========================================================================

    public $orderReference;


    // Public Methods
    // =========================================================================

    public function execute($queue)
    {
        $this->setProgress($queue, 1);

        Stamped::log('Order #' . $this->orderReference . ' in queue.');

        $order = Order::find()->reference($this->orderReference)->one();

        if (!$order) {
            throw new Exception('Order #' . $this->orderReference . ' failed.');
        }

        $result = Stamped::$plugin->getService()->sendOrderToStamped($order);

        if (!$result) {
            throw new Exception('Order #' . $this->orderReference . ' failed.');
        }

        return $result;
    }


    // Protected Methods
    // =========================================================================

    protected function defaultDescription(): string
    {
        return 'Sending Order to Stamped';
    }
}
