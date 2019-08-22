<?php
	namespace Trexology\ReviewRateable\Traits;
	use Trexology\ReviewRateable\Models\Rating;
	use Illuminate\Database\Eloquent\Model;
	trait ReviewRateable
	{
		/**
		 * @return \Illuminate\Database\Eloquent\Relations\MorphMany
		 */
		public function ratings()
		{
			return $this->morphMany(Rating::class, 'reviewrateable');
		}
		/**
		 *
		 * @return double
		 */
		public function averageRating($round = null)
		{
			$avgExpression = null;
			if ($round) {
				$avgExpression = 'ROUND(AVG(rating), ' . $round . ') as averageReviewRateable';
			} else {
				$avgExpression = 'AVG(rating) as averageReviewRateable';
			}
			return $this->ratings()
				->selectRaw($avgExpression)
				->get()
				->first()
				->averageReviewRateable;
		}
		/**
		 *
		 * @return int
		 */
		public function countRating()
		{
			return $this->ratings()
				->selectRaw('count(rating) as countReviewRateable')
				->get()
				->first()
				->countReviewRateable;
		}
		/**
		 *
		 * @return double
		 */
		public function sumRating()
		{
			return $this->ratings()
				->selectRaw('SUM(rating) as sumReviewRateable')
				->get()
				->first()
				->sumReviewRateable;
		}
		/**
		 * @param $max
		 *
		 * @return double
		 */
		public function ratingPercent($max = 5)
		{
			$ratings = $this->ratings();
			$quantity = $ratings->count();
			$total = $ratings->selectRaw('SUM(rating) as total')->get()->first()->total;
			return ($quantity * $max) > 0 ? $total / (($quantity * $max) / 100) : 0;
		}
		/**
		 * @param $data
		 * @param Model      $author
		 * @param Model|null $parent
		 *
		 * @return static
		 */
		public function rating($data, Model $author, Model $parent = null)
		{
			return (new Rating())->createRating($this, $data, $author);
		}
		/**
		 * @param $id
		 * @param $data
		 * @param Model|null $parent
		 *
		 * @return mixed
		 */
		public function updateRating($id, $data, Model $parent = null)
		{
			return (new Rating())->updateRating($id, $data);
		}
		/**
		 * @param $id
		 *
		 * @return mixed
		 */
		public function deleteRating($id)
		{
			return (new Rating())->deleteRating($id);
		}
	}