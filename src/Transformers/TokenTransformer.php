<?php

namespace Dandaj\Api\Transformers;

class TokenTransformer extends Transformer
{
	public function transform($token)
    {
		return [
			'token' => $token['token'],
		];
	}
}