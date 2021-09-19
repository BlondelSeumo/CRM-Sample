<?php


    namespace App\Services;


    use App\Helpers\Core\Traits\FileHandler;
    use App\Helpers\CRM\Traits\PaginateTraits;
    use App\Services\Core\BaseService;
    use Illuminate\Database\Eloquent\Model;

    class ApplicationBaseService extends BaseService
    {
        use FileHandler, PaginateTraits;

        /**
         * @param $relation
         * @param $data
         * @return \Illuminate\Support\Collection
         */
        public function prepareFollowersDataBeforeSync($data)
        {
            return collect($data)->map(function ($item, $key) {
                return ['person_id' => $item];
            });
        }

        public function syncAll($relation, $data): void
        {
            $relation->delete();

            collect($data)->filter(fn($item) => $item['value'])->each(fn($item) => $relation->updateOrCreate($item));
        }

        public function followerSyncAll($relation, $data)
        {
            $relation->delete();
            foreach ($data as $key => $item)
                $relation->updateOrCreate($item);
        }

        public function syncTags(Model $model, $data)
        {
            if ($data)
                $model->tags()->create([
                    'name' => $data,
                    'color_code' => random_color_code()
                ]);
        }

        public function profilePicture($profile_picture, Model $model)
        {
            $file_path = $this->uploadImage(
                $profile_picture,
                'avatar'
            );
            $model->profilePicture()->updateOrCreate([
                'type' => 'profile_picture'
            ], [
                'path' => $file_path
            ]);
        }

        public function customFieldSync($customs, $model, $service)
        {
            $service->syncAll($model->customFields(), $customs);
        }


        public function deleteCustomFiled(): self
        {
            $this->model->customFields()->delete();

            return $this;
        }


        public function delete(): self
        {
            $this->model->delete();

            return $this;
        }
    }
