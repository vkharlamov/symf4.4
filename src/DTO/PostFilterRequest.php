<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Validator\Constraints as PublicationFilterAssert;
use Symfony\Component\Validator\Constraints as Assert;
use App\DTO\IRequestDto;

/**
 * Class PostFilterRequest
 *
 * @package App\DTO
 */
class PostFilterRequest implements IRequestDto
{
    public const FIELDS = [
        'status', 'dateFrom', 'dateTo',
    ];
    /**
     * @PublicationFilterAssert\PostStatus
     */
    private $status;

    /**
     * @PublicationFilterAssert\Datepicker()
     * @var string A "d.m.Y" formatted value
     */
    private $dateFrom;

    /**
     * @PublicationFilterAssert\Datepicker()
     * @var string A "d.m.Y" formatted value
     */
    private $dateTo;

    public $request;

//    private $author;

    public function __construct($request)
    {
        $this->setRequest($request);
        $this->status = $this->request->get('status');
        $this->dateFrom = $this->request->get('dateFrom');
        $this->dateTo = $this->request->get('dateTo', null);
//      $this->author = $author;
    }

    public function setRequest($request)
    {
        if ($request instanceof RequestStack) {
            $this->request = $request->getCurrentRequest();
        } elseif ($request instanceof Request) {
            $this->request = $request;
        } else {
            throw new BadRequestHttpException('No Request instance');
        }
    }

    public function getStatus(): string
    {
        return $this->status ?: '';
    }

    public function getDateFrom(): string
    {
        return $this->dateFrom ?: '';
    }

    public function getDateTo(): string
    {
        return $this->dateTo ?: '';
    }

    /*    public function getAuthor(): User
        {
            return $this->author;
        }*/

    /**
     * @param $property
     *
     * @return mixed
     */
    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }
}
