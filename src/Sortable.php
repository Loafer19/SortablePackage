<?php

namespace Loafer;

trait Sortable
{
    protected static function bootSortable()
    {
        self::creating(function ($model) {
            $model->{$model->getSortColumn()} = $model->query()
                ->max($model->getSortColumn()) + 1;
        });

        self::updating(function ($model) {
            if ($model->isDirty($model->getSortColumn())) {
                if ($model->{$model->getSortColumn()} > $model->getOriginal($model->getSortColumn())) {
                    $model->decrementSortIndex();
                }

                $model->incrementSortIndex();
            }
        });
    }

    private function getSortColumn(): string
    {
        return $this->sortColumn ?? 'sort_order';
    }

    private function incrementSortIndex(): void
    {
        $this->query()
            ->when($this->hasNamedScope('sortableScope'), fn ($q) => $q->sortableScope($this))
            ->where($this->getSortColumn(), '<', $this->getOriginal($this->getSortColumn()))
            ->where($this->getSortColumn(), '>=', $this->{$this->getSortColumn()})
            ->increment($this->getSortColumn());
    }

    private function decrementSortIndex(): void
    {
        $this->query()
            ->when($this->hasNamedScope('sortableScope'), fn ($q) => $q->sortableScope($this))
            ->where($this->getSortColumn(), '>', $this->getOriginal($this->getSortColumn()))
            ->where($this->getSortColumn(), '<=', $this->{$this->getSortColumn()})
            ->decrement($this->getSortColumn());
    }

    public function scopeSorted($query)
    {
        return $query->orderBy($this->getSortColumn());
    }
}
