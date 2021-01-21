<?php

declare(strict_types=1);

namespace Asseco\RemoteRelations;

use Asseco\RemoteRelations\App\Contracts\HasRemoteRelations;
use Asseco\RemoteRelations\App\Exceptions\RemoteRelationException;
use Asseco\RemoteRelations\App\Models\RemoteRelation;
use Illuminate\Database\Eloquent\Collection;

class RelationsResolver
{
    protected array $services = [];

    public function __construct()
    {
        $this->services = config('asseco-remote-relations.services');
    }

    /**
     * Resolve single relation.
     *
     * @param RemoteRelation $relation
     * @return array
     * @throws RemoteRelationException
     */
    public function resolveRelation(RemoteRelation $relation): array
    {
        $service = $this->instantiateService($relation->service);

        return $service->resolveRemoteRelation($relation);
    }

    /**
     * Efficiently resolve relation collection grouping it by service/model.
     *
     * @param Collection $relations
     * @return array
     * @throws RemoteRelationException
     */
    public function resolveRelations(Collection $relations): array
    {
        $relationsByService = $relations->groupBy(['service', 'remote_model']);

        $resolved = [];

        foreach ($relationsByService as $serviceClass => $relationsByModel) {
            $service = $this->instantiateService($serviceClass);

            foreach ($relationsByModel as $model => $relationCollection) {
                $resolved = array_merge($resolved, $service->resolveRemoteRelations($model, $relationCollection));
            }
        }

        return $resolved;
    }

    /**
     * @param string $serviceClass
     * @return HasRemoteRelations
     * @throws RemoteRelationException
     */
    protected function instantiateService(string $serviceClass): HasRemoteRelations
    {
        if (!array_key_exists($serviceClass, $this->services)) {
            throw new RemoteRelationException("Service '$serviceClass' is not registered");
        }

        return new $this->services[$serviceClass];
    }
}
