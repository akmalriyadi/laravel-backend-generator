<?php

namespace AkmalRiyadi\LaravelBackendGenerator\Enums;

enum ItemOptions: string
{
    case DEFAULT = 'default';
    case WITH_TRASHED = 'withTrashed';
    case ONLY_TRASHED = 'onlyTrashed';
}