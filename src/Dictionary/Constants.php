<?php

namespace App\Dictionary;

class Constants
{
    public const POST_PER_PAGE = 2;
    public const USER_PER_PAGE = 2;
    public const DEFAULT_PAGE = 1;

    /** Post */
    public const POST_MIN_TITLE = 5;
    public const POST_MAX_TITLE = 50;

    public const POST_MIN_CONTENT = 15;
    public const POST_MAX_CONTENT = 150;

    /** Comment */
    public const COMMENT_MIN_LENGTH = 10;
    public const COMMENT_MAX_LENGTH = 500;
}