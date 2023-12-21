<?php

namespace YgoProDeckClient\Normalizer;

use YgoProDeckClient\Attribute\ModelProperty;
use YgoProDeckClient\Attribute\SubModel;
use YgoProDeckClient\Model\Model;
use YgoProDeckClient\Util\StringUtil;

class Denormalizer
{
    public function denormalize(array $data, string $class): mixed
    {
        try {
            if (!\class_exists($class)) {
                throw new \RuntimeException('Cannot denormalize into a non-existing class ' . $class);
            }

            $args = [];
            $reflection = new \ReflectionClass($class);

            foreach ($reflection->getProperties() as $reflectionProperty) {
                $name = $reflectionProperty->name;
                $type = $reflectionProperty->getType();
                $attributes = $reflectionProperty->getAttributes(ModelProperty::class);
                /** @var ModelProperty|null $modelProperty */
                $modelProperty = null;

                if (0 < \count($attributes)) {
                    $modelProperty = $attributes[0]->newInstance();
                    $name = $modelProperty->name;
                }

                // Handling model properties
                if (!($type instanceof \ReflectionUnionType) && !$type->isBuiltin() && Model::class === \get_parent_class($type->getName())) {
                    // Gets the SubModel attribute
                    $attributes = $reflectionProperty->getAttributes(SubModel::class);

                    if (0 === count($attributes)) {
                        throw new \RuntimeException(\sprintf('Cannot deserialize %s property into %s instance : missing SubModel attribute',
                            $name,
                            $type->getName()
                        ));
                    }

                    $subModelData = $this->getDataForSubModel(
                        data: $reflectionProperty->name !== $name ? $data[$name][0] : $data,
                        subModel: $attributes[0]->newInstance()
                    );

                    // Transforms the array into the sub model instance
                    $args[$reflectionProperty->name] = $this->denormalize($subModelData, $type->getName());

                    continue;
                }

                if (isset($data[$name])) {
                    $value = $data[$name];
                } else {
                    $camelName = StringUtil::camelToSnake($name);

                    if (isset($data[$camelName])) {
                        $value = $data[$camelName];
                    } else {
                        continue;
                    }
                }

                // Handling custom deserialization method
                if (null !== $modelProperty) {
                    $deserializationMethod = $modelProperty->deserializationMethod;

                    if (null !== $deserializationMethod) {
                        $args[$reflectionProperty->name] = $reflection->getMethod($deserializationMethod)->invokeArgs(null, [$args, $value]);
                        continue;
                    }
                }

                if ($type instanceof \ReflectionUnionType) {
                    $args[$reflectionProperty->name] = $value;
                }
                elseif ($type->isBuiltin()) {
                    // Arrays can be sub models or enum collections
                    if ('array' === $type->getName()) {
                        // Handling enum collections
                        if (null !== $modelProperty?->enumCollection) {
                            $enumReflection = new \ReflectionEnum($modelProperty->enumCollection);
                            $values = [];

                            foreach ($value as $v) {
                                $values[] = $this->getEnumValue($enumReflection, $v);
                            }

                            $args[$reflectionProperty->name] = $values;
                        }
                        // Handling sub models
                        else {
                            // If the array is a matched to a SubModel, then tries to convert the array into the Model instance
                            $attributes = $reflectionProperty->getAttributes(SubModel::class);

                            if (0 === \count($attributes)) {
                                $args[$reflectionProperty->name] = $value;
                                continue;
                            }

                            /** @var SubModel $subModel */
                            $subModel = $attributes[0]->newInstance();

                            if ($subModel->collection) {
                                $array = [];

                                foreach ($value as $v) {
                                    $array[] = $this->denormalize($this->getDataForSubModel($v, $subModel), $subModel->model);
                                }

                                $args[$reflectionProperty->name] = $array;
                            } else {
                                // Transforms the array into the sub model instance
                                $args[$reflectionProperty->name] = $this->denormalize($this->getDataForSubModel($value[0], $subModel), $subModel->model);
                            }
                        }
                    } else {
                        $args[$reflectionProperty->name] = $value;
                    }
                }
                elseif ($this->isEnumType($type)) {
                    $args[$reflectionProperty->name] = $this->getEnumValue(new \ReflectionEnum($type->getName()), $value);
                }
                elseif ($this->isDateTimeType($type)) {
                    $args[$reflectionProperty->name] = new \DateTime($value);
                }
                else {
                    throw new \Exception(\sprintf('Cannot deserialize property %s : unknown type %s',
                        $reflectionProperty->name,
                        $type->getName()
                    ));
                }
            }

            return $reflection->newInstanceArgs($args);
        }
        catch (\Throwable $e) {
            throw new \RuntimeException(\sprintf('Something went wrong while denormalizing into %s instance : %s',
                $class,
                $e->getMessage()
            ));
        }
    }

    private function isEnumType(\ReflectionNamedType $type): bool
    {
        return (new \ReflectionClass($type->getName()))->isEnum();
    }

    private function isDateTimeType(\ReflectionNamedType $type): bool
    {
        return \DateTimeInterface::class === $type->getName();
    }

    private function getDataForSubModel(array $data, SubModel $subModel): array
    {
        // Gets all properties starting with "root" value
        $subModelData = \array_filter(
            $data,
            fn (string $key) => \str_starts_with($key, $subModel->root),
            ARRAY_FILTER_USE_KEY
        );

        // Creates an array removing the root part from the keys
        return \array_combine(
            \array_map(fn (string $key) => \str_replace($subModel->root, '', $key), \array_keys($subModelData)),
            \array_values($subModelData)
        );
    }

    private function getEnumValue(\ReflectionEnum $reflectionEnum, string $value): mixed
    {
        $fromMethod = $reflectionEnum->getMethod('tryFrom');
        $enumVal = $fromMethod->invokeArgs(null, [$value]);

        if (null === $enumVal) {
            $enumVal = $fromMethod->invokeArgs(null, [\strtolower($value)]);
        }

        return $enumVal;
    }
}